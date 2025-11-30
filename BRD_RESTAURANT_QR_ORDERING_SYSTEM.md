# Business Requirements Document (BRD)
## Restaurant QR Ordering System - Dapoer Katendjo

---

### Document Control

| Item | Details |
|------|---------|
| **Project Name** | Restaurant QR Ordering System (Dapoer Katendjo) |
| **Document Version** | 1.0 |
| **Date Created** | November 2025 |
| **Prepared By** | Development Team |
| **Status** | Final Implementation |

---

## 1. Executive Summary

### 1.1 Purpose
This document outlines the business requirements for the Restaurant QR Ordering System developed for Dapoer Katendjo, a comprehensive digital solution that enables contactless ordering, payment processing, and kitchen management through QR code technology.

### 1.2 Project Scope
The system provides an end-to-end restaurant management solution including customer ordering via QR codes, POS functionality, kitchen order management, real-time payment processing with QRIS, and comprehensive reporting capabilities.

### 1.3 Business Value
- **Contactless Service**: Reduce physical contact through QR-based ordering
- **Operational Efficiency**: Streamline order flow from customer to kitchen
- **Payment Integration**: Automate payment processing with QRIS support
- **Real-time Updates**: Instant order status synchronization across all roles
- **Data-Driven Decisions**: Comprehensive reporting and analytics

---

## 2. Business Objectives

### 2.1 Primary Objectives
1. **Digitalize Ordering Process**: Enable customers to order independently via QR codes
2. **Improve Order Accuracy**: Reduce errors through digital order management
3. **Enhance Customer Experience**: Provide intuitive, fast ordering interface
4. **Streamline Kitchen Operations**: Centralize order management for kitchen staff
5. **Enable Multiple Payment Methods**: Support cash and QRIS payments

### 2.2 Success Criteria
- 90% of orders placed via QR code system
- Order processing time reduced by 40%
- Payment processing success rate > 95%
- Zero order discrepancies between customer and kitchen
- System uptime > 99%

---

## 3. Stakeholder Analysis

### 3.1 Primary Stakeholders & System Roles

The system has **3 distinct user roles**, with Admin serving dual functions:

| Stakeholder | System Role | Access Level | Primary Needs |
|-------------|-------------|--------------|---------------|
| **Admin/Cashier** | `admin` | Full Access | POS operation, menu management, order management, reporting |
| **Kitchen Staff** | `kitchen` | Kitchen Dashboard Only | Order queue, preparation workflow, status updates |
| **Customers** | `customer` or `guest` | Menu & Ordering Only | Easy ordering, payment, order tracking |

**Note**: The Admin role combines both administrative functions and cashier/POS operations. There is no separate "Cashier" role in the system.

### 3.2 Stakeholder Requirements Summary
- **Admin/Cashier**: 
  - POS for walk-in customers
  - Full menu and category management
  - Order monitoring and confirmation
  - Payment processing (Cash/QRIS)
  - Customer account management
  - QR code generation for tables
  - Business reports and analytics
  
- **Kitchen Staff**: 
  - Clear order display by status
  - Easy status update workflow
  - Order preparation queue
  - Special instruction visibility
  - Restricted access (kitchen only)
  
- **Customers**: 
  - Mobile-friendly ordering interface
  - Multiple payment options
  - Order status tracking
  - Order history (session-based for guests, permanent for registered users)

---

## 4. Functional Requirements

### 4.1 Customer Features

#### 4.1.1 QR Code Scanning & Menu Access
- **FR-C001**: Customer can scan QR code on table to access menu
- **FR-C002**: System automatically captures table number from QR code
- **FR-C003**: Menu displays all available items with images, prices, and descriptions
- **FR-C004**: Items are organized by categories with horizontal scroll navigation
- **FR-C005**: Out-of-stock items are clearly marked and disabled

#### 4.1.2 Shopping Cart & Ordering
- **FR-C006**: Add items to cart with quantity selection
- **FR-C007**: View cart summary with real-time total calculation
- **FR-C008**: Modify cart items (update quantity or remove)
- **FR-C009**: Add special instructions per item
- **FR-C010**: Clear entire cart functionality

#### 4.1.3 Checkout & Payment
- **FR-C011**: Select payment method (Cash or QRIS)
- **FR-C012**: For QRIS: Display QR code for payment
- **FR-C013**: Auto-check payment status every 5 seconds
- **FR-C014**: Redirect to order success page after payment
- **FR-C015**: Display order number for reference

#### 4.1.4 Order Tracking
- **FR-C016**: View order status (Pending, Preparing, Ready)
- **FR-C017**: Access order history for session (guest) or account (logged-in)
- **FR-C018**: View detailed order information including items and payment status
- **FR-C019**: Guest order history persists during session only
- **FR-C020**: Registered user order history stored permanently

#### 4.1.5 User Account Management
- **FR-C021**: Register account with phone number and OTP verification
- **FR-C022**: Login with email or phone number
- **FR-C023**: View and edit profile (name and address only)
- **FR-C024**: Phone number locked after registration (cannot be changed)

### 4.2 Admin Features

#### 4.2.1 Dashboard & Overview
- **FR-A001**: View today's revenue and order statistics
- **FR-A002**: Display pending, preparing, and ready order counts
- **FR-A003**: Quick access links to all management modules
- **FR-A004**: Real-time data updates without page refresh

#### 4.2.2 POS (Point of Sale)
- **FR-A005**: Create orders on behalf of customers
- **FR-A006**: Browse menu with category filter and search
- **FR-A007**: Add items to order with quantity
- **FR-A008**: Select order type (Dine-in, Takeaway, Delivery)
- **FR-A009**: Choose payment method (Cash, QRIS, Card)
- **FR-A010**: For Cash: Calculate change automatically
- **FR-A011**: For QRIS: Generate and display QR code
- **FR-A012**: Auto-poll QRIS payment status
- **FR-A013**: Print receipt after successful payment

#### 4.2.3 Menu Management
- **FR-A014**: Create, read, update, delete menu items
- **FR-A015**: Upload menu item images
- **FR-A016**: Set menu availability status
- **FR-A017**: Manage stock levels
- **FR-A018**: Assign menu items to categories
- **FR-A019**: View all menus in sortable table

#### 4.2.4 Category Management
- **FR-A020**: Create, read, update, delete categories
- **FR-A021**: Set category active/inactive status
- **FR-A022**: View menu count per category

#### 4.2.5 Order Management
- **FR-A023**: View all orders with filters (status, date, payment method)
- **FR-A024**: View detailed order information
- **FR-A025**: Update order status manually
- **FR-A026**: Confirm cash payments
- **FR-A027**: View payment method and amount paid
- **FR-A028**: Access order receipt for printing
- **FR-A029**: Check QRIS payment status

#### 4.2.6 Customer Management
- **FR-A030**: View all registered customers
- **FR-A031**: View customer order history
- **FR-A032**: Activate/deactivate customer accounts
- **FR-A033**: Delete customer accounts
- **FR-A034**: View customer contact information

#### 4.2.7 QR Code Management
- **FR-A035**: Generate QR codes for table numbers
- **FR-A036**: Preview QR code before printing
- **FR-A037**: Print QR codes for tables
- **FR-A038**: QR codes embed table number automatically

#### 4.2.8 Reporting & Analytics
- **FR-A039**: View daily sales reports
- **FR-A040**: View weekly sales reports
- **FR-A041**: View monthly sales reports
- **FR-A042**: Export reports to PDF
- **FR-A043**: View top-selling menu items
- **FR-A044**: View revenue by payment method
- **FR-A045**: View order count trends

### 4.3 Kitchen Features

#### 4.3.1 Order Dashboard
- **FR-K001**: View orders by status tabs (Confirmed, Preparing, Ready)
- **FR-K002**: Display order details (items, quantities, table number)
- **FR-K003**: Show special instructions clearly
- **FR-K004**: Display order creation time

#### 4.3.2 Order Processing
- **FR-K005**: Mark order as "Preparing" when work starts
- **FR-K006**: Mark order as "Ready" when completed
- **FR-K007**: Auto-refresh order list every 30 seconds
- **FR-K008**: Visual/audio notification for new orders (future enhancement)

#### 4.3.3 Access Control
- **FR-K009**: Kitchen staff can only access kitchen dashboard
- **FR-K010**: Automatic redirect if accessing admin/customer pages
- **FR-K011**: Cannot view payment information
- **FR-K012**: Cannot modify menu or settings

---

## 5. Non-Functional Requirements

### 5.1 Performance
- **NFR-001**: Page load time < 3 seconds on 4G connection
- **NFR-002**: Support 50 concurrent users
- **NFR-003**: Database query response < 500ms
- **NFR-004**: QRIS payment check response < 2 seconds

### 5.2 Usability
- **NFR-005**: Mobile-responsive design (Bootstrap 5)
- **NFR-006**: Intuitive UI with clear visual hierarchy
- **NFR-007**: Maximum 3 clicks to complete any action
- **NFR-008**: Support Indonesian language
- **NFR-009**: Accessible color contrast ratios

### 5.3 Security
- **NFR-010**: Password hashing with bcrypt
- **NFR-011**: Session-based authentication
- **NFR-012**: Role-based access control (Admin, Kitchen, Customer)
- **NFR-013**: CSRF protection on all forms
- **NFR-014**: SQL injection prevention via ORM
- **NFR-015**: XSS protection via framework
- **NFR-016**: Secure QRIS payment integration

### 5.4 Reliability
- **NFR-017**: 99% uptime target
- **NFR-018**: Graceful error handling
- **NFR-019**: Database transaction rollback on failures
- **NFR-020**: Payment webhook retry mechanism

### 5.5 Maintainability
- **NFR-021**: Modular code architecture (MVC)
- **NFR-022**: Comprehensive code comments
- **NFR-023**: Database migrations for schema changes
- **NFR-024**: Logging for debugging and monitoring

---

## 6. System Features by Module

### 6.1 Authentication & Authorization
- Multi-method login (email/phone + password)
- OTP verification for registration
- Password reset functionality
- Session management
- Role-based middleware protection

### 6.2 Menu & Category System
- Hierarchical category organization
- Rich menu item metadata (name, description, price, image)
- Stock tracking and availability status
- Dynamic filtering and search

### 6.3 Cart & Checkout System
- Session-based cart for guests
- Persistent cart for logged-in users
- Real-time price calculation
- Tax computation (10%)
- Multiple payment method support

### 6.4 Payment Integration
- **Cash Payments**: Manual confirmation with change calculation
- **QRIS Payments**: Midtrans integration with auto-status checking
- Payment status tracking (Pending, Paid)
- Receipt generation

### 6.5 Order Management System
- Order lifecycle: Pending → Confirmed → Preparing → Ready → Delivered
- Auto-status updates based on payment
- Order number generation (ORD-YYYYMMDD-XXXXXX)
- Special instructions per item

### 6.6 Kitchen Management
- Isolated dashboard for kitchen staff
- Order queue visualization
- Status update workflow
- Auto-refresh capabilities

### 6.7 Reporting System
- Sales reports (daily, weekly, monthly)
- Revenue analysis by payment method
- Top products report
- Order trend charts
- PDF export functionality

---

## 7. Technical Requirements

### 7.1 Technology Stack
- **Backend Framework**: Laravel 12.x
- **Frontend**: Blade Templates + Bootstrap 5
- **Database**: MySQL 8.0+
- **Server**: Apache/Nginx + PHP 8.4+
- **Payment Gateway**: Midtrans (QRIS)
- **Session Storage**: File-based (upgradeable to Redis)

### 7.2 Third-Party Integrations
- **Midtrans Core API**: QRIS payment processing
- **Bootstrap Icons**: UI iconography
- **Animate.css**: UI animations
- **Chart.js**: Reporting visualizations (future)

### 7.3 Deployment Requirements
- Hosting compatible with Laravel (InfinityFree, cPanel, VPS)
- PHP 8.4+ with required extensions
- MySQL database with CREATE/ALTER privileges
- HTTPS for secure transactions
- Webhook accessibility for payment callbacks

### 7.4 Database Structure
- **Users**: Authentication and profile data
- **Categories**: Menu categorization
- **Menus**: Product catalog
- **Orders**: Order header information
- **Order Items**: Order line items
- **Sessions**: User session management

---

## 8. User Workflows

### 8.1 Customer Order Flow (Guest)
1. Scan QR code on table
2. Browse menu by category
3. Add items to cart
4. Proceed to checkout
5. Enter name and select payment method
6. For QRIS: Scan payment QR code
7. Wait for payment confirmation
8. View order success page
9. Track order status
10. Receive order when ready

### 8.2 Admin POS Flow
1. Login to admin panel
2. Navigate to POS
3. Select menu items
4. Enter customer details
5. Choose payment method
6. Process payment (cash/QRIS)
7. Print receipt
8. Monitor order in dashboard

### 8.3 Kitchen Order Flow
1. Login to kitchen dashboard
2. View new orders in "Confirmed" tab
3. Start preparing order (move to "Preparing")
4. Complete preparation
5. Mark as "Ready"
6. Notify customer/waiter

---

## 9. Data Requirements

### 9.1 Data Retention
- **Order Data**: Permanent retention for reporting
- **User Accounts**: Retained until manually deleted
- **Cart Sessions**: 24-hour expiry for guests
- **Logs**: 30-day retention

### 9.2 Data Privacy
- Customer phone numbers locked after registration
- Payment information not stored (only reference)
- Admin cannot view customer passwords
- GDPR-compliant data handling

---

## 10. Constraints & Assumptions

### 10.1 Constraints
- Single restaurant location (not multi-tenant)
- Indonesian language only
- No delivery tracking integration
- No inventory management beyond stock count
- No customer loyalty program

### 10.2 Assumptions
- Stable internet connectivity at restaurant
- Customers have smartphones with camera
- Midtrans account setup and verified
- Tables have unique numbers
- Kitchen has display device for dashboard

---

## 11. Future Enhancements

### 11.1 Phase 2 Features
- Multi-language support (English, etc.)
- Push notifications for order updates
- Customer loyalty points system
- Delivery partner integration
- Inventory management module
- Employee time tracking

### 11.2 Phase 3 Features
- Multi-branch support
- Advanced analytics and forecasting
- Table reservation system
- Customer feedback and ratings
- Mobile application (native iOS/Android)

---

## 12. Glossary

| Term | Definition |
|------|------------|
| **BRD** | Business Requirements Document |
| **POS** | Point of Sale system |
| **QRIS** | Quick Response Code Indonesian Standard (payment) |
| **OTP** | One-Time Password for verification |
| **Guest** | Non-registered customer |
| **Session** | Temporary browser storage for guest users |

---

## 13. Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| **Project Owner** | __________ | __________ | ___/___/___ |
| **Technical Lead** | __________ | __________ | ___/___/___ |
| **Business Analyst** | __________ | __________ | ___/___/___ |

---

### Document Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | Nov 2025 | Development Team | Initial complete BRD |

---

**END OF DOCUMENT**
