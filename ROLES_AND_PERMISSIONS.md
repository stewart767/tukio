# Roles and Permissions Documentation

This document lists all available roles and their associated permissions in the system. Super Admin can use this as a reference when assigning permissions to staff members.

## System Roles

The system has three main roles:

1. **super_admin** - Full system access
2. **event_admin** - Event management access
3. **staff** - Event attendance management

---

## Role: Super Admin (`super_admin`)

### Description
Full system access - can manage users, templates, settings, and view all reports.

### Permissions & Capabilities

#### User Management
- ✅ **View Users** (`admin.users.index`) - List all users
- ✅ **Create Users** (`admin.users.create`, `admin.users.store`) - Create new users with any role
- ✅ **View User Details** (`admin.users.show`) - View individual user details
- ✅ **Edit Users** (`admin.users.edit`, `admin.users.update`) - Update user information, role, and status
- ✅ **Delete Users** (`admin.users.destroy`) - Remove users from system

#### Template Management
- ✅ **View Templates** (`admin.templates.index`) - List all invitation templates
- ✅ **Create Templates** (`admin.templates.create`, `admin.templates.store`) - Create new invitation templates
- ✅ **View Template Details** (`admin.templates.show`) - View template details
- ✅ **Edit Templates** (`admin.templates.edit`, `admin.templates.update`) - Update template content and settings
- ✅ **Delete Templates** (`admin.templates.destroy`) - Remove templates
- ✅ **Toggle Template Status** (`admin.templates.toggleActive`) - Activate/deactivate templates

#### Host Management
- ✅ **View Hosts** (`admin.hosts.index`) - List all hosts with metrics
- ✅ **Create Hosts** (`admin.hosts.create`, `admin.hosts.store`) - Create new host accounts
- ✅ **Edit Hosts** (`admin.hosts.edit`, `admin.hosts.update`) - Update host information
- ✅ **Delete Hosts** (`admin.hosts.destroy`) - Remove host accounts
- ✅ **Finish Event** (`admin.hosts.finish`) - Mark host event as finished and deactivate account
- ✅ **Assign Staff to Events** (`admin.hosts.assign-staff`, `admin.hosts.assign-staff.store`) - Assign staff members to host events

#### Reports & Analytics
- ✅ **View Reports** (`admin.reports.index`) - Access comprehensive system reports including:
  - Total events, upcoming events, completed events
  - Active hosts count
  - Total guests and invitations
  - WhatsApp delivery statistics
  - Event trends and analytics
  - Top events by guest count
  - Delivery and attendance summaries

#### Page Management
- ✅ **View Pages** (`admin.pages.index`) - List all CMS pages
- ✅ **Edit Pages** (`admin.pages.edit`, `admin.pages.update`) - Update page content, SEO, and settings

#### System Settings
- ✅ **View Settings** (`admin.settings.index`) - Access all system settings
- ✅ **Update System Settings** (`admin.settings.system`) - Configure:
  - System name, tagline, timezone, currency
  - Contact information (address, email, phone)
  - Logo and favicon
  - Social media links
- ✅ **Update Email Settings** (`admin.settings.email`) - Configure SMTP settings
- ✅ **Update Payment Settings** (`admin.settings.payment`) - Configure Flutterwave payment gateway
- ✅ **Update SMS Settings** (`admin.settings.sms`) - Configure Beem SMS gateway
- ✅ **Update WhatsApp Settings** (`admin.settings.whatsapp`) - Configure Meta and Beem WhatsApp gateways
- ✅ **Create Database Backup** (`admin.settings.backup.create`) - Create system backups
- ✅ **Restore Database Backup** (`admin.settings.backup.restore`) - Restore from backup
- ✅ **Download Backup** (`admin.settings.backup.download`) - Download backup files

#### Profile Management
- ✅ **View Profile** (`admin.profile.show`) - View own profile
- ✅ **Edit Profile** (`admin.profile.edit`, `admin.profile.update`) - Update profile information
- ✅ **Change Password** (`admin.profile.password.update`) - Update password

#### Dashboard
- ✅ **View Dashboard** (`dashboard`) - Access admin dashboard with:
  - Total hosts, events, guests
  - Total revenue
  - Recent events and users

---

## Role: Event Admin (`event_admin`)

### Description
Can create and manage events, guests, and invitations. Typically assigned to users who manage event operations.

### Permissions & Capabilities

#### Profile Management
- ✅ **View Profile** (`admin.profile.show`) - View own profile
- ✅ **Edit Profile** (`admin.profile.edit`, `admin.profile.update`) - Update profile information
- ✅ **Change Password** (`admin.profile.password.update`) - Update password

#### Dashboard
- ✅ **View Dashboard** (`dashboard`) - Access dashboard with:
  - Own events count
  - Own guests count
  - Active events
  - Recent events
  - Upcoming events

#### Event Access (via Event Policy)
- ✅ **View Own Events** - Can view events they created
- ✅ **Create Events** - Can create new events
- ✅ **Update Own Events** - Can update events they created
- ✅ **Delete Own Events** - Can delete events they created

**Note:** Event Admin permissions for events are managed through the `EventPolicy` class, which allows:
- `viewAny`: Event admins and super admins can view event lists
- `view`: Only super admins or event owners can view specific events
- `create`: Event admins and super admins can create events
- `update`: Only super admins or event owners can update events
- `delete`: Only super admins or event owners can delete events

---

## Role: Staff (`staff`)

### Description
Can scan QR codes and manage event attendance for assigned events.

### Permissions & Capabilities

#### QR Code Scanning
- ✅ **View Scanner** (`staff.scanner`) - Access QR code scanner interface
- ✅ **Scan QR Codes** (`staff.scan`) - Scan and validate invitation codes
- ✅ **Mark Attendance** - Mark guests as attended when QR code is scanned

#### Attendance Management
- ✅ **View Attendance** (`staff.attendance`) - View attendance records for assigned events
- ✅ **View Today's Scans** - See all scans performed today for assigned events

#### Event Access
- ✅ **View Assigned Events** - Can only see events they are assigned to (via `event_staff` pivot table or `staff_id` field)
- ❌ **Cannot create, edit, or delete events**
- ❌ **Cannot manage guests or invitations**
- ❌ **Cannot access admin panel**

#### Restrictions
- ❌ **No User Management** - Cannot view, create, edit, or delete users
- ❌ **No Template Management** - Cannot access templates
- ❌ **No Host Management** - Cannot view or manage hosts
- ❌ **No Reports Access** - Cannot view system reports
- ❌ **No Settings Access** - Cannot access system settings
- ❌ **No Profile Management** - Limited profile access (may need to be added)

---

## Permission Assignment Guide for Super Admin

When assigning permissions to staff members, consider the following:

### For Event Management Staff
- **Role:** `event_admin`
- **Use Case:** Users who need to create and manage events, guests, and invitations
- **Access:** Full event management for their own events

### For Event Day Staff
- **Role:** `staff`
- **Use Case:** Users who only need to scan QR codes and mark attendance
- **Access:** Scanner interface and attendance viewing for assigned events only
- **Assignment:** Must be assigned to specific events by Super Admin via Host Management

### For System Administrators
- **Role:** `super_admin`
- **Use Case:** Full system access for managing all aspects of the platform
- **Access:** All permissions listed above

---

## Permission Matrix

| Permission | Super Admin | Event Admin | Staff |
|------------|-------------|-------------|-------|
| **User Management** |
| View Users | ✅ | ❌ | ❌ |
| Create Users | ✅ | ❌ | ❌ |
| Edit Users | ✅ | ❌ | ❌ |
| Delete Users | ✅ | ❌ | ❌ |
| **Template Management** |
| View Templates | ✅ | ❌ | ❌ |
| Create Templates | ✅ | ❌ | ❌ |
| Edit Templates | ✅ | ❌ | ❌ |
| Delete Templates | ✅ | ❌ | ❌ |
| **Host Management** |
| View Hosts | ✅ | ❌ | ❌ |
| Create Hosts | ✅ | ❌ | ❌ |
| Edit Hosts | ✅ | ❌ | ❌ |
| Delete Hosts | ✅ | ❌ | ❌ |
| Assign Staff | ✅ | ❌ | ❌ |
| **Event Management** |
| View All Events | ✅ | ✅ (Own only) | ✅ (Assigned only) |
| Create Events | ✅ | ✅ | ❌ |
| Edit Events | ✅ | ✅ (Own only) | ❌ |
| Delete Events | ✅ | ✅ (Own only) | ❌ |
| **Reports** |
| View Reports | ✅ | ❌ | ❌ |
| **Settings** |
| System Settings | ✅ | ❌ | ❌ |
| Email Settings | ✅ | ❌ | ❌ |
| Payment Settings | ✅ | ❌ | ❌ |
| SMS Settings | ✅ | ❌ | ❌ |
| WhatsApp Settings | ✅ | ❌ | ❌ |
| Backups | ✅ | ❌ | ❌ |
| **Attendance** |
| Scan QR Codes | ✅ | ❌ | ✅ |
| View Attendance | ✅ | ❌ | ✅ (Assigned events) |
| **Profile** |
| View Profile | ✅ | ✅ | ❌ |
| Edit Profile | ✅ | ✅ | ❌ |

---

## Notes

1. **Role Assignment:** Only Super Admin can create and assign roles to users through the User Management interface.

2. **Event Assignment:** Staff members must be assigned to specific events by Super Admin through the Host Management → Assign Staff feature.

3. **Policy-Based Access:** Event access is controlled by the `EventPolicy` class, which ensures Event Admins can only manage their own events.

4. **Middleware Protection:** Routes are protected by `RoleMiddleware` which checks user roles before allowing access.

5. **Future Enhancements:** Consider implementing a granular permission system (e.g., using Laravel Spatie Permission package) if you need more fine-grained control over permissions within roles.

---

## Quick Reference: Route Permissions

### Super Admin Only Routes
- `/admin/users/*` - User management
- `/admin/templates/*` - Template management
- `/admin/hosts/*` - Host management
- `/admin/reports` - Reports
- `/admin/pages/*` - Page management
- `/admin/settings/*` - System settings

### Super Admin & Event Admin Routes
- `/admin/profile/*` - Profile management
- `/dashboard` - Dashboard (different views based on role)

### Staff Only Routes
- `/staff/scanner` - QR code scanner
- `/staff/scan` - Scan endpoint
- `/staff/attendance` - Attendance viewing

---

**Last Updated:** Based on current codebase analysis
**Version:** 1.0
