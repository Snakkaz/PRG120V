# 📋 TESTING & DEPLOYMENT GUIDE
# PRG120V - Student- og Klassebehandling

## ✅ Pre-Deployment Checklist

### 1. Database Setup (phpMyAdmin)

1. **Logg inn på phpMyAdmin**
   - URL: Via USN database-portal
   - Database: stpet1155
   - Bruker: stpet1155
   - Passord: d991stpet1155

2. **Verifiser eller opprett tabeller**
   
   Kjør følgende SQL hvis tabellene ikke eksisterer:
   ```sql
   CREATE TABLE IF NOT EXISTS klasse (
     klassekode CHAR(5) NOT NULL,
     klassenavn VARCHAR(50) NOT NULL,
     studiumkode VARCHAR(50) NOT NULL,
     PRIMARY KEY (klassekode)
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

   CREATE TABLE IF NOT EXISTS student (
     brukernavn CHAR(7) NOT NULL,
     fornavn VARCHAR(50) NOT NULL,
     etternavn VARCHAR(50) NOT NULL,
     klassekode CHAR(5) NOT NULL,
     PRIMARY KEY (brukernavn),
     FOREIGN KEY (klassekode) REFERENCES klasse(klassekode)
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
   ```

3. **Last inn eksempeldata (valgfritt)**
   - Åpne `database_setup.sql` i phpMyAdmin
   - Kjør hele SQL-skriptet
   - Dette vil opprette testdata for klasser og studenter

### 2. Local Testing (Optional)

If testing locally before deployment:

```bash
# Start PHP built-in server
php -S localhost:8000

# Open browser
# Visit: http://localhost:8000
```

### 3. Deployment to USN Dokploy

**Option A: Automatic Deployment (Recommended)**
The application will auto-deploy when you push to GitHub main branch.

```bash
# From your local development machine
git add .
git commit -m "Your commit message"
git push origin main
```

**Option B: Manual Deployment via SSH**

```bash
# SSH into Dokploy server
ssh stpet1155@dokploy.usn.no

# Navigate to application directory
cd /path/to/app/stpet1155-prg120v

# Pull latest changes
git pull origin main

# Set permissions (if needed)
chmod -R 755 .
```

## 🧪 Testing Steps

### Step 1: Test Database Connection

1. Navigate to: `https://dokploy.usn.no/app/stpet1155-prg120v/test_db.php`
2. You should see:
   - ✅ Database connection successful
   - ✅ Table 'klasse' exists
   - ✅ Table 'student' exists
   - ✅ Query execution successful
3. If any test fails, check database credentials in `db.php`

### Step 2: Test Homepage

1. Navigate to: `https://dokploy.usn.no/app/stpet1155-prg120v/`
2. Verify:
   - Homepage loads correctly
   - Statistics show correct numbers
   - All navigation links work

### Step 3: Test Class Management

1. Go to: `https://dokploy.usn.no/app/stpet1155-prg120v/klasse.php`

2. **Test CREATE:**
   - Fill in form with:
     - Klassekode: TEST1
     - Klassenavn: Test Klasse
     - Studiumkode: TEST
   - Click "Opprett klasse"
   - Verify success message appears
   - Verify class appears in table

3. **Test READ:**
   - Verify all classes are displayed
   - Check that student count is shown

4. **Test DELETE:**
   - Try to delete a class with students (should fail with error)
   - Delete a class without students (should succeed)

### Step 4: Test Student Management

1. Go to: `https://dokploy.usn.no/app/stpet1155-prg120v/student.php`

2. **Test CREATE:**
   - Fill in form with:
     - Brukernavn: test123
     - Fornavn: Test
     - Etternavn: Testesen
     - Klasse: Select from dropdown
   - Click "Opprett student"
   - Verify success message appears
   - Verify student appears in table

3. **Test READ:**
   - Verify all students are displayed with class information
   - Check sorting (should be by last name)

4. **Test DELETE:**
   - Delete a student
   - Verify success message
   - Verify student is removed from table

### Step 5: Test Validation

1. **Class Form Validation:**
   - Try to create class with klassekode not exactly 5 characters
   - Try to create duplicate klassekode
   - Leave fields empty
   - Verify appropriate error messages

2. **Student Form Validation:**
   - Try to create student with brukernavn not exactly 7 characters
   - Try to create duplicate brukernavn
   - Try to assign to non-existent class
   - Leave fields empty
   - Verify appropriate error messages

### Step 6: Test Edge Cases

1. **Foreign Key Constraint:**
   - Create a class with students
   - Try to delete the class
   - Should show error: "Kan ikke slette klasse - det finnes X student(er)"

2. **Cascading Effects:**
   - Delete all students from a class
   - Now delete the class
   - Should succeed

3. **Special Characters:**
   - Test with Norwegian characters (æ, ø, å)
   - Verify they display correctly

## 🐛 Troubleshooting

### Issue: Database Connection Failed

**Solution:**
1. Verify database credentials in `db.php`
2. Check if MySQL server is running
3. Verify network connectivity to mysql.usn.no
4. Check database user permissions

### Issue: Tables Not Found

**Solution:**
1. Run `database_setup.sql` in phpMyAdmin
2. Verify table names are exactly: `klasse` and `student`
3. Check database name is: `stpet1155`

### Issue: Cannot Add Student

**Solution:**
1. Ensure at least one class exists
2. Verify brukernavn is exactly 7 characters
3. Check that klassekode exists in klasse table

### Issue: Cannot Delete Class

**Solution:**
1. Check if class has students
2. Delete students first, then class
3. Or keep the class if students need it

### Issue: Norwegian Characters Not Displaying

**Solution:**
1. Verify database charset is utf8mb4
2. Check `db.php` has: `$conn->set_charset("utf8mb4");`
3. Ensure HTML files have: `<meta charset="UTF-8">`

## 📊 Performance Testing

### Expected Response Times:
- Homepage load: < 1 second
- Class list: < 1 second
- Student list: < 2 seconds (with JOIN)
- Insert operations: < 500ms
- Delete operations: < 500ms

### Load Testing (Optional):
```bash
# Using Apache Bench (if installed)
ab -n 100 -c 10 https://dokploy.usn.no/app/stpet1155-prg120v/
```

## 🔒 Security Checklist

- ✅ SQL injection protection (prepared statements)
- ✅ XSS protection (htmlspecialchars)
- ✅ Input validation (server-side)
- ✅ Foreign key constraints
- ✅ Confirmation dialogs for destructive actions

## 📝 Final Verification

Before submitting, verify:

- [ ] All files are in GitHub repository
- [ ] README.md is complete and accurate
- [ ] Application is live on USN Dokploy
- [ ] Database is properly configured
- [ ] All CRUD operations work correctly
- [ ] Error handling works as expected
- [ ] UI is responsive on mobile and desktop
- [ ] Norwegian characters display correctly
- [ ] No PHP errors or warnings
- [ ] Code is well-documented

## 🎓 Submission Checklist

- [ ] GitHub repository URL: https://github.com/Snakkaz/PRG120V
- [ ] Live application URL: https://dokploy.usn.no/app/stpet1155-prg120v/
- [ ] Database: stpet1155 @ mysql.usn.no
- [ ] All features implemented and tested
- [ ] Documentation complete

## 🚀 Quick Deployment Commands

```bash
# Clone repository (if needed)
git clone https://github.com/Snakkaz/PRG120V.git
cd PRG120V

# Make changes
# ... edit files ...

# Commit and push
git add .
git commit -m "Description of changes"
git push origin main

# Application auto-deploys to Dokploy
```

## 📞 Support

If issues persist:
1. Check GitHub Issues: https://github.com/Snakkaz/PRG120V/issues
2. Review error logs on server
3. Contact USN IT support for server/database issues

---

**Last Updated:** October 2025
**Version:** 1.0
