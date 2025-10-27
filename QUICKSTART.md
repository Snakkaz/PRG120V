# 🚀 QUICK START GUIDE
# PRG120V - Student- og Klassebehandling

## 📦 What's Included

This complete PHP web application includes:

✅ **Core Files:**
- `index.php` - Homepage with navigation and statistics
- `klasse.php` - Class management (CRUD)
- `student.php` - Student management (CRUD)
- `db.php` - Database connection handler
- `style.css` - Modern responsive design

✅ **Utility Files:**
- `test_db.php` - Database connection tester
- `database_setup.sql` - SQL schema + sample data
- `deploy.sh` - Deployment script
- `.gitignore` - Git ignore rules

✅ **Documentation:**
- `README.md` - Complete project documentation
- `TESTING.md` - Detailed testing guide
- `QUICKSTART.md` - This file

## 🎯 Quick Access Links

| Resource | URL |
|----------|-----|
| **Live Application** | https://dokploy.usn.no/app/stpet1155-prg120v/ |
| **GitHub Repository** | https://github.com/Snakkaz/PRG120V |
| **Database Test** | https://dokploy.usn.no/app/stpet1155-prg120v/test_db.php |

## ⚡ 3-Minute Setup

### 1. Verify Database (1 min)
```
1. Visit: https://dokploy.usn.no/app/stpet1155-prg120v/test_db.php
2. Check all tests pass ✅
3. If tables missing, run database_setup.sql in phpMyAdmin
```

### 2. Test Application (1 min)
```
1. Visit: https://dokploy.usn.no/app/stpet1155-prg120v/
2. Navigate to "Klasser"
3. Create a test class
4. Navigate to "Studenter"
5. Create a test student
```

### 3. Verify Everything Works (1 min)
```
✓ Can create classes
✓ Can create students
✓ Can view all data
✓ Can delete records
✓ Error messages show correctly
```

## 🗄️ Database Quick Reference

**Connection Details:**
- Host: `mysql-ait.usn.no` (or run `troubleshoot_db.php` to find correct one)
- Database: `stpet1155`
- User: `stpet1155`
- Password: `d991stpet1155`

**If connection fails:** Visit `troubleshoot_db.php` to test all hostname alternatives.

**Tables:**
- `klasse` (klassekode, klassenavn, studiumkode)
- `student` (brukernavn, fornavn, etternavn, klassekode)

## 📝 Quick Features Test

### Test Class Management
```
1. Go to: Klasser
2. Add: IT101 | Informasjonsteknologi 1. klasse | ITE
3. Verify it appears in table
4. Try to delete (should work if no students)
```

### Test Student Management
```
1. Go to: Studenter
2. Add: test123 | Test | Testesen | [Select IT101]
3. Verify it appears in table
4. Delete the student
```

### Test Validation
```
1. Try creating class with 4 characters (should fail)
2. Try creating duplicate klassekode (should fail)
3. Try creating student without selecting class (should fail)
4. Try deleting class with students (should fail)
```

## 🔧 Common Tasks

### Update Code
```bash
# Make changes locally
git add .
git commit -m "Your changes"
git push origin main
# Auto-deploys to Dokploy
```

### Add Sample Data
```sql
-- Run in phpMyAdmin:
INSERT INTO klasse VALUES ('IT101', 'IT 1. klasse', 'ITE');
INSERT INTO student VALUES ('stpet11', 'Petter', 'Petterson', 'IT101');
```

### Reset Database
```sql
-- Run in phpMyAdmin:
DELETE FROM student;
DELETE FROM klasse;
-- Or run entire database_setup.sql
```

## 🐛 Quick Troubleshooting

| Problem | Solution |
|---------|----------|
| Can't connect to DB | Check credentials in db.php |
| Tables not found | Run database_setup.sql |
| Can't add student | Create a class first |
| Can't delete class | Delete students first |
| Norwegian chars broken | Check UTF-8 encoding |

## 📱 Testing Checklist

- [ ] Homepage loads
- [ ] Can create class
- [ ] Can view classes
- [ ] Can delete class (without students)
- [ ] Can create student
- [ ] Can view students
- [ ] Can delete student
- [ ] Validation works
- [ ] Error messages appear
- [ ] Mobile responsive

## 🎓 For Grading/Demonstration

Show these features:

1. **Homepage** - Statistics and navigation
2. **Class CRUD** - Create, Read, Delete operations
3. **Student CRUD** - Create, Read, Delete operations
4. **Dropdown** - Class selection uses listbox
5. **Validation** - Error handling for invalid input
6. **Database** - Show tables in phpMyAdmin
7. **Foreign Key** - Demonstrate referential integrity
8. **Responsive** - Show on mobile/tablet view

## 🚀 Deployment Status

✅ **GitHub Repository:** https://github.com/Snakkaz/PRG120V
✅ **Auto-Deploy:** Configured for USN Dokploy
✅ **Live URL:** https://dokploy.usn.no/app/stpet1155-prg120v/
✅ **Database:** Connected to stpet1155@mysql-ait.usn.no

## 📚 Documentation

- **Full Documentation:** See README.md
- **Testing Guide:** See TESTING.md
- **Code Comments:** All files have detailed comments

## ⚡ Need Help?

1. Check `README.md` for complete documentation
2. Check `TESTING.md` for detailed testing steps
3. Review code comments in PHP files
4. Check GitHub issues for known problems

---

**Ready to use!** 🎉

Start here: https://dokploy.usn.no/app/stpet1155-prg120v/
