# TopMost Villa Booking - Complete Project Structure

## 📁 Full Directory Tree

```
flutter_app/
├── README.md                              # Comprehensive documentation
├── QUICKSTART.md                          # Quick start guide
├── PROJECT_STRUCTURE.md                   # This file
├── pubspec.yaml                           # Dependencies configuration
│
├── lib/
│   ├── main.dart                          # App entry point (73 lines)
│   │
│   ├── models/                            # Data Models Layer
│   │   ├── user.dart                      # User model with JSON serialization
│   │   ├── villa.dart                     # Villa model with image handling
│   │   └── booking.dart                   # Booking model with calculations
│   │
│   ├── services/                          # API Service Layer
│   │   └── api_service.dart               # HTTP client, auth, CRUD operations
│   │
│   ├── providers/                         # State Management Layer
│   │   ├── auth_provider.dart             # Authentication state
│   │   ├── villa_provider.dart            # Villa data state
│   │   └── booking_provider.dart          # Booking data state
│   │
│   ├── screens/                           # UI Screens
│   │   ├── splash_screen.dart             # Initial loading & auth check
│   │   │
│   │   ├── auth/                          # Authentication Screens
│   │   │   ├── login_screen.dart          # Multi-role login
│   │   │   └── register_screen.dart       # User registration
│   │   │
│   │   ├── home/                          # Home Screens
│   │   │   └── home_screen.dart           # Main landing page
│   │   │
│   │   ├── villas/                        # Villa Screens
│   │   │   ├── villas_screen.dart         # Villa listing with search
│   │   │   └── villa_detail_screen.dart   # Detailed villa view
│   │   │
│   │   ├── booking/                       # Booking Screens
│   │   │   └── booking_screen.dart        # Booking flow & confirmation
│   │   │
│   │   └── dashboard/                     # Dashboard Screens
│   │       ├── user_dashboard_screen.dart # User profile & bookings
│   │       ├── owner_dashboard_screen.dart # Owner stats & villas
│   │       └── admin_dashboard_screen.dart # Admin overview
│   │
│   └── widgets/                           # Reusable Components
│       └── villa_card.dart                # Villa display card
│
└── assets/                                # (Create these folders)
    ├── images/
    └── icons/
```

## 📊 File Statistics

| Category | Files | Lines of Code |
|----------|-------|---------------|
| Models | 3 | ~200 |
| Services | 1 | ~200 |
| Providers | 3 | ~300 |
| Screens | 10 | ~1500 |
| Widgets | 1 | ~150 |
| **Total** | **18** | **~2350** |

## 🎯 Core Components Breakdown

### 1. Models (Data Layer)

#### user.dart
- Properties: id, name, email, phone, profile image, KYC status, wallet
- Methods: fromJson(), toJson()
- Used for: User authentication and profile display

#### villa.dart
- Properties: id, title, description, location, price, bedrooms, bathrooms, guests, images, amenities
- Methods: fromJson(), toJson(), mainImage getter, amenitiesList getter
- Used for: Villa display and booking

#### booking.dart
- Properties: id, userId, villaId, checkIn, checkOut, guests, totalPrice, status
- Methods: fromJson(), toJson(), nights calculation
- Used for: Booking management and history

### 2. Services (API Layer)

#### api_service.dart
Contains all API communication methods:
- `login()` - Multi-role authentication
- `register()` - User registration
- `getVillas()` - Fetch all villas
- `getVillaById()` - Fetch single villa
- `getUserBookings()` - Fetch user bookings
- `createBooking()` - Create new booking
- `searchVillas()` - Search with filters
- `getToken()`, `saveToken()`, `removeToken()` - Token management
- `getHeaders()` - Auth header injection

### 3. Providers (State Management)

#### auth_provider.dart
Manages authentication state:
- User login with role selection
- User registration
- Auto-login on app start
- Logout functionality
- Session persistence
- Error handling

#### villa_provider.dart
Manages villa data:
- Fetch all villas
- Fetch villa details
- Search/filter villas
- Selected villa state
- Loading and error states

#### booking_provider.dart
Manages booking operations:
- Fetch user bookings
- Create new bookings
- Booking list state
- Loading and error states

### 4. Screens (UI Layer)

#### Authentication Flow
1. **SplashScreen** - Auto-login check and role-based navigation
2. **LoginScreen** - Multi-role login form
3. **RegisterScreen** - User registration form

#### Main App Flow
4. **HomeScreen** - Featured villas, welcome message, quick navigation
5. **VillasScreen** - Full villa list with search
6. **VillaDetailScreen** - Detailed view with image carousel
7. **BookingScreen** - Date selection, guest count, price calculation

#### Dashboards
8. **UserDashboardScreen** - Profile info, bookings, wallet
9. **OwnerDashboardScreen** - Villa stats, bookings
10. **AdminDashboardScreen** - Platform overview

### 5. Widgets (Reusable Components)

#### villa_card.dart
Reusable villa display card with:
- Cached image
- Title, location, price
- Bedrooms, bathrooms, guests
- Status badge
- Tap navigation

## 🔄 Data Flow

```
User Action → Screen → Provider → API Service → Backend
                ↓          ↓
              Widget    State Update → UI Refresh
```

## 🎨 Design System

### Colors
- Primary: `#1E3A8A` (Deep Blue)
- Secondary: `#3B82F6` (Blue)
- Success: `Colors.green`
- Warning: `Colors.orange`
- Error: `Colors.red`

### Typography
- Font Family: Poppins (Google Fonts)
- Heading: Bold, 24-28px
- Body: Regular, 14-16px
- Caption: Regular, 12-14px

### Spacing
- Small: 8px
- Medium: 16px
- Large: 24px
- XLarge: 32px

### Border Radius
- Cards: 16px
- Buttons: 12px
- Inputs: 12px

## 🛣️ Navigation Routes

```dart
'/' → SplashScreen
'/login' → LoginScreen
'/register' → RegisterScreen
'/home' → HomeScreen
'/villas' → VillasScreen
'/villa-detail' → VillaDetailScreen (with Villa argument)
'/booking' → BookingScreen (with Villa argument)
'/user-dashboard' → UserDashboardScreen
'/owner-dashboard' → OwnerDashboardScreen
'/admin-dashboard' → AdminDashboardScreen
```

## 📦 Dependencies Used

### Core
- **flutter** - UI framework
- **provider** - State management
- **http** - API communication

### UI/UX
- **google_fonts** - Poppins font family
- **cached_network_image** - Image caching
- **carousel_slider** - Image galleries
- **shimmer** - Loading effects

### Utilities
- **shared_preferences** - Local storage
- **intl** - Date/currency formatting
- **flutter_svg** - SVG support

## 🔐 Authentication Flow

```
1. App Launch → SplashScreen
2. Check SharedPreferences for token
3. If token exists:
   - Load user data
   - Navigate to role-specific dashboard
4. If no token:
   - Navigate to LoginScreen
5. After login/register:
   - Save token and user data
   - Navigate to appropriate screen
```

## 💾 Local Storage

Uses SharedPreferences to store:
- `token` - JWT authentication token
- `role` - User role (user/owner/admin)
- `user` - JSON string of user data

## 🌐 API Integration

Base URL: `https://topmost.in/api`

### Endpoints Used
- POST `/user-register` - Registration
- POST `/user-login` - User login
- POST `/owner-login` - Owner login
- POST `/admin-login` - Admin login
- GET `/villas` - List villas
- GET `/villas/{id}` - Villa details
- GET `/search` - Search villas
- GET `/user/bookings` - User bookings
- POST `/bookings` - Create booking

## 🧪 Testing Strategy

### Manual Testing Checklist
- [ ] Registration flow
- [ ] Login with all roles
- [ ] Browse villas
- [ ] Search functionality
- [ ] Villa details view
- [ ] Booking flow
- [ ] View bookings
- [ ] Logout
- [ ] Session persistence

### Unit Testing (Future)
- Model serialization
- API service methods
- Provider state changes
- Date calculations

## 🚀 Performance Optimizations

1. **Image Caching** - Reduces network calls
2. **Lazy Loading** - Lists load on scroll
3. **State Management** - Efficient UI updates
4. **JSON Serialization** - Fast data parsing
5. **Route-based Code Splitting** - Smaller initial bundle

## 📈 Scalability Considerations

### Current Architecture Supports:
- Adding new screens easily
- Extending API functionality
- New user roles
- Additional providers
- More villa filters
- Payment integration
- Push notifications

### Future Enhancements Path:
1. Add payment provider
2. Add notification service
3. Extend villa filters
4. Add review system
5. Implement chat
6. Add analytics
7. Multi-language support

## 📝 Code Style Guide

### Naming Conventions
- Classes: PascalCase (`UserDashboardScreen`)
- Variables: camelCase (`checkInDate`)
- Constants: camelCase with const (`const baseUrl`)
- Files: snake_case (`user_dashboard_screen.dart`)

### Best Practices Followed
- Single Responsibility Principle
- DRY (Don't Repeat Yourself)
- Stateless widgets where possible
- Provider for state management
- Proper error handling
- Loading states for async operations
- User feedback for actions

## 🎓 Learning Resources

To understand this codebase:
1. Start with `main.dart` - See app structure
2. Review `api_service.dart` - Understand API calls
3. Study providers - See state management
4. Examine screens - Understand UI flow
5. Check models - Learn data structure

## 🔍 Quick Reference

### Add New Screen
1. Create file in `lib/screens/`
2. Add route in `main.dart`
3. Import necessary providers
4. Use Consumer for state

### Add New API Call
1. Add method in `api_service.dart`
2. Update relevant provider
3. Call from screen

### Modify Theme
Edit `ThemeData` in `main.dart`

### Add New Model
1. Create file in `lib/models/`
2. Add fromJson/toJson methods
3. Update API service
4. Update provider

---

**Total Project**: 20 files, ~2500 lines of code
**Framework**: Flutter 3.0+
**State Management**: Provider
**Architecture**: Clean Architecture with Provider
