import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:google_fonts/google_fonts.dart';
import 'providers/auth_provider.dart';
import 'providers/villa_provider.dart';
import 'providers/booking_provider.dart';
import 'screens/splash_screen.dart';
import 'screens/auth/login_screen.dart';
import 'screens/auth/register_screen.dart';
import 'screens/home/home_screen.dart';
import 'screens/villas/villas_screen.dart';
import 'screens/villas/villa_detail_screen.dart';
import 'screens/booking/booking_screen.dart';
import 'screens/dashboard/user_dashboard_screen.dart';
import 'screens/dashboard/owner_dashboard_screen.dart';
import 'screens/dashboard/admin_dashboard_screen.dart';
import 'screens/debug_screen.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();

  FlutterError.onError = (FlutterErrorDetails details) {
    FlutterError.presentError(details);
  };

  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider()),
        ChangeNotifierProvider(create: (_) => VillaProvider()),
        ChangeNotifierProvider(create: (_) => BookingProvider()),
      ],
      child: MaterialApp(
        title: 'TopMost Villa Booking',
        debugShowCheckedModeBanner: false,
        theme: ThemeData(
          colorScheme: ColorScheme.fromSeed(
            seedColor: const Color(0xFF1E3A8A),
            primary: const Color(0xFF1E3A8A),
            secondary: const Color(0xFF3B82F6),
          ),
          fontFamily: GoogleFonts.poppins().fontFamily,
          useMaterial3: true,
          appBarTheme: const AppBarTheme(
            elevation: 0,
            centerTitle: true,
            backgroundColor: Color(0xFF1E3A8A),
            foregroundColor: Colors.white,
          ),
          elevatedButtonTheme: ElevatedButtonThemeData(
            style: ElevatedButton.styleFrom(
              backgroundColor: const Color(0xFF1E3A8A),
              foregroundColor: Colors.white,
              padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 16),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(12),
              ),
            ),
          ),
        ),
        initialRoute: '/',
        routes: {
          '/': (context) => const SplashScreen(),
          '/login': (context) => const LoginScreen(),
          '/register': (context) => const RegisterScreen(),
          '/home': (context) => const HomeScreen(),
          '/villas': (context) => const VillasScreen(),
          '/villa-detail': (context) => const VillaDetailScreen(),
          '/booking': (context) => const BookingScreen(),
          '/user-dashboard': (context) => const UserDashboardScreen(),
          '/owner-dashboard': (context) => const OwnerDashboardScreen(),
          '/admin-dashboard': (context) => const AdminDashboardScreen(),
          '/debug': (context) => const DebugScreen(),
        },
      ),
    );
  }
}
