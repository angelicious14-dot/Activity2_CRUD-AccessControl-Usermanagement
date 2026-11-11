ACTIVITY 2 - ACCOUNT HUB (PHP + MYSQL)

IMPORTANT
- This is a NEW project folder. Keep Activity 1 unchanged.
- Activity 2 connects to the SAME database: signin_register.
- Do not import database_setup.sql as a new database. Run it ONCE in phpMyAdmin
  after selecting the existing signin_register database. It adds the role column.
- If the role column already exists, do not run the ALTER TABLE command again.

XAMPP SETUP
1. Extract the Activity2 folder into C:\xampp\htdocs\ (Windows).
   On another device, use the htdocs folder inside your XAMPP installation.
2. Start Apache and MySQL.
3. Open http://localhost/phpmyadmin
4. Select the existing signin_register database.
5. Open the SQL tab and run the contents of database_setup.sql ONCE.
6. Open database.php and confirm MySQL username/password.
   Typical XAMPP defaults: username root, blank password.
7. Open http://localhost/Activity2/

ADMIN ACCOUNT
1. Register a normal account in Activity 2, or use an existing Activity 1 account.
2. In phpMyAdmin, select signin_register, then open the SQL tab.
3. Run: UPDATE users SET role='admin' WHERE username='YOUR_USERNAME';
4. Replace YOUR_USERNAME with your real username.
5. Sign out and sign in again. You should be redirected to admin.php.

ACTIVITY 1 ACCOUNT COMPATIBILITY
- Activity 2 login supports passwords stored using PHP password_hash().
- It also supports legacy plain-text passwords from Activity 1 and upgrades them
  to password_hash() after a successful login.
- If Activity 1 used MD5, SHA1, or another custom password format, this login
  needs to be adjusted to match that exact Activity 1 implementation.

FEATURES
- Sign in and registration
- Session-based access control
- Admin and regular-user roles
- Admin dashboard and account statistics
- Create, Read, Update, Delete user accounts
- Prepared SQL statements, password hashing, and CSRF tokens

TESTING
- Test registration with a new email and username.
- Test login with an existing Activity 1 account.
- Test regular user access to dashboard.php.
- Test that a regular user cannot open admin.php or users management pages.
- Sign in as admin and test Add, Edit, and Delete.
- Do not delete your own admin account while testing.

NOTE
This is a classroom starter project. For public production deployment, add HTTPS,
rate limiting, stronger audit logging, and a more complete password recovery flow.
