<!DOCTYPE html>
<html>
<head>
    <title>Test Form Submission</title>
</head>
<body>
    <h1>Test Voucher Form</h1>
    
    <form method="POST" action="{{ route('admin.vouchers.store') }}" id="testForm">
        @csrf
        <input type="hidden" name="user_type" value="registered">
        
        <input type="text" name="code" value="TESTCODE123" required><br>
        <input type="text" name="name" value="Test Voucher" required><br>
        <select name="type" required>
            <option value="percentage">Percentage</option>
        </select><br>
        <input type="number" name="value" value="10" step="any" required><br>
        <input type="number" name="min_transaction" value="0" step="any" required><br>
        <input type="number" name="user_limit" value="1" required><br>
        <input type="checkbox" name="is_active" checked><br>
        
        <button type="submit">Submit Test</button>
    </form>
    
    <script>
        document.getElementById('testForm').addEventListener('submit', function(e) {
            console.log('Form submitting...');
            console.log('Action:', this.action);
            console.log('Method:', this.method);
            
            const formData = new FormData(this);
            console.log('Form data:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ':', value);
            }
        });
    </script>
</body>
</html>
