# BidCraft - Simplified Permission System

## 🎯 **Clear & Simple Permissions**

### **Bid Form Permissions:**
- `view bid recommendations` - **Can see the bid form**
- `create bid recommendations` - **Can create/edit bid data** (Admin only)
- `edit bid recommendations` - **Can modify existing bids** (Admin only)  
- `delete bid recommendations` - **Can delete bids** (Admin only)

### **AI Request Permissions:**
- `view ai requests` - **Can see AI request data** (Admin only)
- `create ai requests` - **Can use "Ask AI" button**
- `edit ai requests` - **Can modify AI requests** (Admin only)
- `delete ai requests` - **Can delete AI requests** (Admin only)
- `view ai history` - **Can see AI request history**

## 👥 **Role Definitions**

### **🔴 Super Admin (Manager)**
**Access Level:** Full Control
**Permissions:** ALL permissions
- Can view and manage everything
- Can create/edit/delete users and roles
- Can manage all templates and bids
- Can see all AI requests and history

### **🔵 Team Member (User)**  
**Access Level:** Limited - View Form + Use AI Only
**Permissions:**
- `view dashboard` - **Can access main dashboard**
- `view bid recommendations` - **Can see the bid form**
- `create ai requests` - **Can click "Ask AI" button**
- `view ai history` - **Can see their AI request history**
- `view settings` - **Can view their own profile**

**What Team Members CAN'T do:**
- ❌ Create/edit/delete users
- ❌ Manage templates
- ❌ Edit bid recommendations
- ❌ View other users' data
- ❌ Manage permissions/roles

## 🔧 **Controller Security**

### **BidRecommendationController**
```php
// View bid form requires 'view bid recommendations' permission
$this->middleware('permission:view bid recommendations')->only(['index']);

// Using AI requires 'create ai requests' permission  
$this->middleware('permission:create ai requests')->only(['askAI']);

// Viewing history requires 'view ai history' permission
$this->middleware('permission:view ai history')->only(['history']);
```

## 🔄 **Automatic Permission Assignment**

### **When Creating New Users:**
✅ When you create a new user with **"Team Member"** role:
- **Role** is assigned: `Team Member`  
- **Permissions** are **automatically assigned**:
  - `view dashboard`
  - `view bid recommendations` 
  - `create ai requests`
  - `view ai history`
  - `view settings`

### **When Assigning Roles:**
✅ When you assign **"Team Member"** role to existing user:
- Same permissions are **automatically assigned**
- Ensures consistent access regardless of how role was assigned

### **Benefits:**
- **No manual permission assignment needed**
- **Consistent access** for all Team Members
- **Automatic setup** - just assign the role!

## 🚀 **Setup Instructions**

### **1. Refresh Permissions:**
Visit: `http://your-domain/refresh-permissions`
- This updates roles and permissions with new settings

### **2. Check Permission Summary:**
Visit: `http://your-domain/permission-summary`
- Shows current roles and their permissions

### **3. Test Access:**
- **Login as Super Admin**: `admin@bidcraft.com` / `password123`
  - Should have access to everything
- **Login as Team Member**: `user@bidcraft.com` / `password123`  
  - Should only see bid form and be able to use AI

### **4. Create/Assign Team Members:**
- **Option 1**: Create new user with "Team Member" role
  - ✅ Permissions assigned automatically!
- **Option 2**: Assign "Team Member" role to existing user
  - ✅ Permissions assigned automatically!
- **Result**: User can immediately access bid form and use AI

## ✅ **Expected Behavior**

### **Team Member User Flow:**
1. **Login** → Can access dashboard
2. **Click "Bid Recommendations"** → Can see the form (view permission)
3. **Fill out form and click "Ask AI"** → AI works (create ai requests permission)
4. **View history** → Can see their AI requests (view ai history permission)
5. **Try to access User Management** → ❌ Access denied (no permission)
6. **Try to access Template Editor** → ❌ Access denied (no permission)

### **Super Admin User Flow:**
1. **Login** → Full access to everything
2. **Can manage users, roles, permissions**
3. **Can access all modules**
4. **Can see all data and history**

This simplified system makes it crystal clear what each role can do! 🎯