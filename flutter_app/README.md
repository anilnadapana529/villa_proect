# TopMost Villa Booking Flutter App

A complete Flutter mobile application for luxury villa booking with authentication, villa browsing, booking management, and role-based dashboards.

## Features

### Authentication
- **User Registration** - Sign up with name, email, phone, and password
- **Multi-role Login** - Login as User, Villa Owner, or Administrator
- **Secure Authentication** - JWT token-based authentication with automatic session management
- **Auto-login** - Remembers user session across app restarts

### User Features
- **Browse Villas** - View featured villas with images, amenities, and pricing
- **Search & Filter** - Search villas by location
- **Villa Details** - Detailed view with image carousel, amenities, and descriptions
- **Book Villas** - Select dates, number of guests, and view pricing breakdown
- **My Bookings** - View all your bookings with status tracking
- **Profile Management** - View profile information and wallet balance

### Owner Features
- **Owner Dashboard** - View villa statistics and booking information
- **Manage Villas** - Track villa performance
- **Booking Management** - Monitor pending and confirmed bookings

### Admin Features
- **Admin Dashboard** - Overview of platform statistics
- **User Management** - Monitor total users and owners
- **Villa Management** - Track all villas on the platform
- **Booking Oversight** - View all platform bookings

## Architecture

### Clean Architecture Structure
```
lib/
├── main.dart                 # App entry point
├── models/                   # Data models
│   ├── user.dart
│   ├── villa.dart
│   └── booking.dart
├── services/                 # API services
│   └── api_service.dart
├── providers/                # State management
│   ├── auth_provider.dart
│   ├── villa_provider.dart
│   └── booking_provider.dart
├── screens/                  # UI screens
│   ├── splash_screen.dart
│   ├── auth/
│   │   ├── login_screen.dart
│   │   └── register_screen.dart
│   ├── home/
│   │   └── home_screen.dart
│   ├── villas/
│   │   ├── villas_screen.dart
│   │   └── villa_detail_screen.dart
│   ├── booking/
│   │   └── booking_screen.dart
│   └── dashboard/
│       ├── user_dashboard_screen.dart
│       ├── owner_dashboard_screen.dart
│       └── admin_dashboard_screen.dart
└── widgets/                  # Reusable widgets
    └── villa_card.dart
```

## Dependencies

```yaml
dependencies:
  flutter:
    sdk: flutter
  cupertino_icons: ^1.0.2
  http: ^1.1.0                    # HTTP requests
  provider: ^6.1.1                # State management
  shared_preferences: ^2.2.2      # Local storage
  cached_network_image: ^3.3.1    # Image caching
  intl: ^0.18.1                   # Date formatting
  flutter_svg: ^2.0.9             # SVG support
  google_fonts: ^6.1.0            # Custom fonts
  carousel_slider: ^4.2.1         # Image carousel
  shimmer: ^3.0.0                 # Loading effects
```

## Setup Instructions

### Prerequisites
- Flutter SDK (3.0.0 or higher)
- Dart SDK
- Android Studio / Xcode for emulator
- Active backend API at `https://topmost.in/api`

### Installation Steps

1. **Clone or extract the project**
   ```bash
   cd flutter_app
   ```

2. **Install dependencies**
   ```bash
   flutter pub get
   ```

3. **Verify Flutter installation**
   ```bash
   flutter doctor
   ```

4. **Run the app**
   ```bash
   # For Android
   flutter run

   # For iOS
   flutter run -d ios

   # For specific device
   flutter devices  # List available devices
   flutter run -d <device_id>
   ```

### API Configuration

The app connects to: `https://topmost.in/api`

If you need to change the API endpoint, modify:
```dart
// lib/services/api_service.dart
static const String baseUrl = 'https://your-api-url.com/api';
```

## API Endpoints Used

### Authentication
- `POST /api/user-register` - User registration
- `POST /api/user-login` - User login
- `POST /api/owner-login` - Owner login
- `POST /api/admin-login` - Admin login

### Villas
- `GET /api/villas` - Get all villas
- `GET /api/villas/{id}` - Get villa details
- `GET /api/search` - Search villas

### Bookings
- `GET /api/user/bookings` - Get user bookings
- `POST /api/bookings` - Create new booking

## State Management

The app uses **Provider** for state management with three main providers:

1. **AuthProvider** - Manages authentication state, login, register, and logout
2. **VillaProvider** - Manages villa data, fetching, and search
3. **BookingProvider** - Manages booking data and creation

## Testing

### Login Credentials

**New User:**
1. Go to Register screen
2. Fill in details and create account

**Existing User (from database):**
- You can create a new user through registration
- For admin/owner login, use credentials from your database

## Building for Release

### Android APK
```bash
flutter build apk --release
```

### Android App Bundle
```bash
flutter build appbundle --release
```

### iOS
```bash
flutter build ios --release
```

## Features Showcase

### Authentication Flow
1. **Splash Screen** - Checks authentication status
2. **Login Screen** - Multi-role login (User/Owner/Admin)
3. **Register Screen** - New user registration
4. **Auto-navigation** - Redirects based on role

### Villa Browsing
- **Featured Villas** - Homepage displays featured properties
- **Image Carousel** - Multiple images per villa
- **Details View** - Complete property information
- **Search** - Filter by location and criteria

### Booking Process
1. Select villa
2. Choose check-in/check-out dates
3. Select number of guests
4. View price breakdown
5. Confirm booking
6. View in dashboard

## Responsive Design

- Material Design 3
- Adaptive layouts for different screen sizes
- Smooth animations and transitions
- Image caching for performance
- Pull-to-refresh functionality

## Security Features

- JWT token authentication
- Secure token storage using SharedPreferences
- Automatic token injection in API calls
- Session persistence across app restarts
- Logout functionality with confirmation

## Performance Optimizations

- **Cached Network Images** - Reduces bandwidth and improves load times
- **Lazy Loading** - Loads data on demand
- **State Management** - Efficient UI updates
- **Image Optimization** - Proper image sizing and caching

## Error Handling

- Network error handling
- User-friendly error messages
- Loading states for async operations
- Form validation
- API error responses

## Future Enhancements

- Push notifications for booking updates
- Payment gateway integration
- Map integration for villa locations
- Rating and review system
- Chat support
- Image upload for owner villas
- Advanced filters (price range, amenities)
- Calendar availability view
- Multi-language support

## Troubleshooting

### Common Issues

**1. Dependencies not resolving**
```bash
flutter clean
flutter pub get
```

**2. Build errors**
```bash
flutter clean
flutter pub get
cd android && ./gradlew clean
cd ..
flutter run
```

**3. API connection issues**
- Check internet connection
- Verify API URL is correct
- Check backend server status

## Project Structure Details

### Models
- **User** - User profile data with wallet and KYC status
- **Villa** - Complete villa information with images and amenities
- **Booking** - Booking details with dates, guests, and pricing

### Providers
- Handle business logic
- Manage app state
- Communicate with API service
- Notify UI of changes

### Services
- API communication layer
- Token management
- Request/response handling
- Error handling

## Support

For issues or questions:
- Check the README
- Review API documentation
- Verify backend connectivity
- Check Flutter version compatibility

## License

This project is proprietary software for TopMost Villa Booking platform.

## Version

**Current Version:** 1.0.0+1

**Last Updated:** November 2025
