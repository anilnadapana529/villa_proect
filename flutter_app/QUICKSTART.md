# TopMost Villa Booking - Quick Start Guide

## 🚀 Get Started in 5 Minutes

### Step 1: Prerequisites
```bash
# Check Flutter is installed
flutter --version

# Should show Flutter 3.0.0 or higher
```

### Step 2: Install Dependencies
```bash
cd flutter_app
flutter pub get
```

### Step 3: Run the App
```bash
# List available devices
flutter devices

# Run on connected device
flutter run
```

### Step 4: Test the App

#### Create a New Account
1. Open the app
2. Tap "Sign Up" on login screen
3. Fill in your details:
   - Full Name: Your Name
   - Email: your@email.com
   - Phone: 1234567890
   - Password: password123
4. Tap "Sign Up"
5. You'll be logged in automatically

#### Browse Villas
1. View featured villas on home screen
2. Tap "Browse Villas" or any villa card
3. Tap "View Details" to see full information
4. Scroll through images using carousel

#### Make a Booking
1. On villa detail page, tap "Book Now"
2. Select check-in date
3. Select check-out date
4. Adjust number of guests
5. Review total price
6. Tap "Confirm Booking"

#### View Your Bookings
1. Tap user icon in top right (home screen)
2. Or navigate to "My Dashboard"
3. See all your bookings with status

## 📱 App Features

### ✅ Working Features
- User registration and login
- Multi-role authentication (User/Owner/Admin)
- Browse villas with images
- Search villas by location
- View villa details with carousel
- Book villas with date selection
- View booking history
- User dashboard with profile info
- Logout functionality

### 🎨 UI Highlights
- Material Design 3
- Custom blue theme (#1E3A8A)
- Google Fonts (Poppins)
- Smooth animations
- Image caching
- Pull-to-refresh
- Loading states

## 🔧 Configuration

### Change API URL
Edit `lib/services/api_service.dart`:
```dart
static const String baseUrl = 'https://your-api.com/api';
```

### Customize Theme
Edit `lib/main.dart`:
```dart
colorScheme: ColorScheme.fromSeed(
  seedColor: const Color(0xYOURCOLOR),
  // ... customize colors
),
```

## 📦 Build for Release

### Android APK
```bash
flutter build apk --release
```
APK location: `build/app/outputs/flutter-apk/app-release.apk`

### Android App Bundle (for Play Store)
```bash
flutter build appbundle --release
```

### iOS (requires Mac)
```bash
flutter build ios --release
```

## 🐛 Troubleshooting

### Issue: Dependencies won't install
```bash
flutter clean
rm -rf pubspec.lock
flutter pub get
```

### Issue: Build fails
```bash
flutter clean
flutter pub get
flutter run
```

### Issue: Images not loading
- Check internet connection
- Verify image URLs in API response
- Check CORS settings on backend

### Issue: Can't login
- Verify API is running at https://topmost.in
- Check credentials
- Look at API response in debug console

## 📖 Code Structure

```
lib/
├── main.dart                    # App entry point with routes
├── models/                      # Data models (User, Villa, Booking)
├── services/api_service.dart    # All API calls
├── providers/                   # State management (Auth, Villa, Booking)
├── screens/                     # All UI screens
│   ├── splash_screen.dart       # Initial loading screen
│   ├── auth/                    # Login & Register
│   ├── home/                    # Home screen
│   ├── villas/                  # Villa listing & details
│   ├── booking/                 # Booking flow
│   └── dashboard/               # User/Owner/Admin dashboards
└── widgets/                     # Reusable UI components
```

## 🎯 Key Files to Know

- **main.dart** - App configuration and routes
- **api_service.dart** - All backend communication
- **auth_provider.dart** - Login/register/logout logic
- **villa_provider.dart** - Villa data management
- **booking_provider.dart** - Booking management

## 💡 Tips

1. **Hot Reload**: Press `r` in terminal while app is running to see changes instantly
2. **Hot Restart**: Press `R` for full restart
3. **Debug**: Use `print()` statements or VS Code debugger
4. **State**: All app state is managed by Providers
5. **Navigation**: Uses named routes defined in main.dart

## 🔐 Test Credentials

Create new account through registration, or use existing database credentials for Owner/Admin roles.

## 📞 Support

- Review README.md for detailed documentation
- Check API documentation at your backend
- Flutter docs: https://docs.flutter.dev

## ✨ Quick Commands

```bash
# Install dependencies
flutter pub get

# Run app
flutter run

# Run on specific device
flutter run -d <device-id>

# Build APK
flutter build apk --release

# Clean build
flutter clean

# Check for issues
flutter doctor

# List devices
flutter devices

# Analyze code
flutter analyze

# Format code
flutter format .
```

## 🎉 You're Ready!

The app is fully functional and ready to use. Start by creating an account and booking your first villa!

---

**Version:** 1.0.0
**Last Updated:** November 2025
**Backend API:** https://topmost.in/api
