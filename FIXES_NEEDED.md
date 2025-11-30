# Critical Fixes Needed

## 1. Design Issues
- [x] Create new header with Poppins font
- [x] Create new footer
- [ ] Fix font sizes (currently too big)
- [ ] Change to white background, dark blue text
- [ ] Remove header/footer from admin and owner dashboards

## 2. Authorization Issues
- [ ] Fix "Unauthorized" error on add-villa
- [ ] Fix "Unauthorized" error on villa-detail view
- [ ] Fix user registration "Unauthorized" error

## 3. Missing Features
- [ ] Add logout button to admin panel
- [ ] Add logout button to owner panel
- [ ] Show commission rate in owner panel
- [ ] Create user signup page
- [ ] Create user login page  
- [ ] Create user dashboard

## 4. Fixes to Apply
1. Admin & Owner dashboards should NOT include header.php and footer.php
2. Villa detail endpoint needs to check if user is logged in OR allow public access
3. Add-villa needs proper token handling
4. User registration endpoint needs to be created
5. Font sizes need to be reduced across all pages
