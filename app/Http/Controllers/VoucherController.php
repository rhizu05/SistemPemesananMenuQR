<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use Carbon\Carbon;

class VoucherController extends Controller
{
    // Admin: List all vouchers
    public function index()
    {
        $vouchers = Voucher::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.vouchers.index', compact('vouchers'));
    }

    // Admin: Show create form
    public function create()
    {
        return view('admin.vouchers.create');
    }

    // Admin: Store new voucher
    public function store(Request $request)
    {
        try {
            \Log::info('Voucher Store Request', [
                'all_data' => $request->all(),
                'has_user_type' => $request->has('user_type'),
                'user_type_value' => $request->input('user_type')
            ]);

            $request->validate([
                'code' => 'required|string|max:50|unique:vouchers,code',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|in:percentage,fixed_amount',
                'value' => 'required|numeric|min:0',
                'min_transaction' => 'required|numeric|min:0',
                'max_discount' => 'nullable|numeric|min:0',
                'quota' => 'nullable|integer|min:1',
                'user_limit' => 'required|integer|min:1',
                'valid_from' => 'nullable|date',
                'valid_until' => 'nullable|date|after_or_equal:valid_from',
            ]);

            $data = $request->all();
            $data['code'] = strtoupper($data['code']); // Auto uppercase
            $data['user_type'] = $request->input('user_type', 'registered'); // Get from request or default
            $data['is_active'] = $request->has('is_active');
            
            $voucher = Voucher::create($data);
            
            \Log::info('Voucher Created Successfully', ['voucher_id' => $voucher->id]);

            return redirect()->route('admin.vouchers.index')->with('success', 'Voucher berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Voucher Store Validation Error', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Voucher Store Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    // Admin: Show edit form
    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers.edit', compact('voucher'));
    }

    // Admin: Update voucher
    public function update(Request $request, $id)
    {
        try {
            \Log::info('Voucher Update Request', [
                'id' => $id,
                'all_data' => $request->all(),
                'has_user_type' => $request->has('user_type'),
                'user_type_value' => $request->input('user_type')
            ]);

            $voucher = Voucher::findOrFail($id);

            $request->validate([
                'code' => 'required|string|max:50|unique:vouchers,code,' . $id,
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|in:percentage,fixed_amount',
                'value' => 'required|numeric|min:0',
                'min_transaction' => 'required|numeric|min:0',
                'max_discount' => 'nullable|numeric|min:0',
                'quota' => 'nullable|integer|min:1',
                'user_limit' => 'required|integer|min:1',
                'valid_from' => 'nullable|date',
                'valid_until' => 'nullable|date|after_or_equal:valid_from',
            ]);

            $data = $request->all();
            $data['code'] = strtoupper($data['code']);
            $data['user_type'] = $request->input('user_type', 'registered'); // Get from request or default
            $data['is_active'] = $request->has('is_active');
            
            $voucher->update($data);
            
            \Log::info('Voucher Updated Successfully', ['voucher_id' => $voucher->id]);

            return redirect()->route('admin.vouchers.index')->with('success', 'Voucher berhasil diupdate!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Voucher Update Validation Error', [
                'id' => $id,
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Voucher Update Error', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    // Admin: Delete voucher
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        
        // Check if voucher has been used
        if ($voucher->used_count > 0) {
            return back()->with('error', 'Tidak dapat menghapus voucher yang sudah pernah digunakan.');
        }

        $voucher->delete();
        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher berhasil dihapus!');
    }

    // Admin: Toggle active status
    public function toggleStatus($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->update(['is_active' => !$voucher->is_active]);

        $status = $voucher->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Voucher berhasil {$status}!");
    }

    // Admin: View usage report
    public function usageReport($id)
    {
        $voucher = Voucher::with(['usages.user', 'usages.order'])->findOrFail($id);
        return view('admin.vouchers.usage-report', compact('voucher'));
    }

    // Customer: View available vouchers
    public function customerIndex()
    {
        $user = auth()->user();
        
        $vouchers = Voucher::active()
            ->valid()
            ->available()
            ->where(function($query) use ($user) {
                $query->where('user_type', 'all');
                
                if ($user) {
                    $query->orWhere('user_type', 'registered');
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Filter vouchers that user can still use
        $availableVouchers = $vouchers->filter(function($voucher) use ($user) {
            return $voucher->canBeUsedBy($user ? $user->id : null);
        });

        return view('customer.vouchers', compact('availableVouchers'));
    }

    // API: Validate voucher code (AJAX)
    public function validate(Request $request)
    {
        // Wajib login untuk menggunakan voucher
        if (!auth()->check()) {
            return response()->json([
                'valid' => false,
                'message' => 'Silakan login terlebih dahulu untuk menggunakan voucher.'
            ], 401);
        }

        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $voucher = Voucher::where('code', strtoupper($request->code))
            ->active()
            ->first();

        if (!$voucher) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode voucher tidak ditemukan.'
            ], 404);
        }

        if (!$voucher->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Voucher sudah tidak berlaku.'
            ], 400);
        }

        if (!$voucher->isAvailable()) {
            return response()->json([
                'valid' => false,
                'message' => 'Kuota voucher sudah habis.'
            ], 400);
        }

        $userId = auth()->id();
        if (!$voucher->canBeUsedBy($userId)) {
            return response()->json([
                'valid' => false,
                'message' => 'Anda sudah mencapai limit penggunaan voucher ini.'
            ], 400);
        }

        if ($request->subtotal < $voucher->min_transaction) {
            return response()->json([
                'valid' => false,
                'message' => 'Minimal belanja Rp ' . number_format($voucher->min_transaction, 0, ',', '.') . ' untuk menggunakan voucher ini.'
            ], 400);
        }

        $discount = $voucher->calculateDiscount($request->subtotal);

        return response()->json([
            'valid' => true,
            'voucher' => [
                'id' => $voucher->id,
                'code' => $voucher->code,
                'name' => $voucher->name,
                'type' => $voucher->type,
                'value' => $voucher->value,
                'discount_amount' => $discount,
            ],
            'message' => 'Voucher berhasil diterapkan!',
            'discount' => $discount,
        ]);
    }
}
