import 'package:flutter/material.dart';
import '../models/booking.dart';
import '../services/api_service.dart';

class BookingProvider with ChangeNotifier {
  List<Booking> _bookings = [];
  bool _isLoading = false;
  String? _error;

  List<Booking> get bookings => _bookings;
  bool get isLoading => _isLoading;
  String? get error => _error;

  Future<void> fetchUserBookings() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      _bookings = await ApiService.getUserBookings();
      _isLoading = false;
      notifyListeners();
    } catch (e) {
      _error = 'Failed to load bookings: $e';
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> createBooking({
    required int villaId,
    required DateTime checkIn,
    required DateTime checkOut,
    required int guests,
    required double totalPrice,
  }) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final result = await ApiService.createBooking(
        villaId: villaId,
        checkIn: checkIn,
        checkOut: checkOut,
        guests: guests,
        totalPrice: totalPrice,
      );

      if (result['success']) {
        await fetchUserBookings();
        _isLoading = false;
        notifyListeners();
        return true;
      } else {
        _error = result['message'];
        _isLoading = false;
        notifyListeners();
        return false;
      }
    } catch (e) {
      _error = 'Booking failed: $e';
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  void clearError() {
    _error = null;
    notifyListeners();
  }
}
