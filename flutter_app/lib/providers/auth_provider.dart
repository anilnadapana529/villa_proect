import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/user.dart';
import '../services/api_service.dart';
import 'dart:convert';

class AuthProvider with ChangeNotifier {
  User? _user;
  String? _token;
  String? _role;
  bool _isLoading = false;
  String? _error;

  User? get user => _user;
  String? get token => _token;
  String? get role => _role;
  bool get isLoading => _isLoading;
  String? get error => _error;
  bool get isAuthenticated => _token != null && _user != null;

  Future<void> checkAuthStatus() async {
    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString('token');
    _role = prefs.getString('role');
    final userJson = prefs.getString('user');

    if (userJson != null && _token != null) {
      _user = User.fromJson(jsonDecode(userJson));
      notifyListeners();
    }
  }

  Future<bool> login({
    required String email,
    required String password,
    required String role,
  }) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final result = await ApiService.login(
        email: email,
        password: password,
        role: role,
      );

      if (result['success']) {
        _token = result['data']['token'];
        _role = role;

        final userData = result['data'][role] ?? result['data']['user'];
        _user = User.fromJson(userData);

        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('user', jsonEncode(userData));

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
      _error = 'Login failed: $e';
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<bool> register({
    required String name,
    required String email,
    required String phone,
    required String password,
  }) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final result = await ApiService.register(
        name: name,
        email: email,
        phone: phone,
        password: password,
      );

      if (result['success']) {
        _token = result['data']['token'];
        _role = 'user';
        _user = User.fromJson(result['data']['user']);

        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('user', jsonEncode(result['data']['user']));

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
      _error = 'Registration failed: $e';
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<void> logout() async {
    await ApiService.removeToken();
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('user');

    _user = null;
    _token = null;
    _role = null;
    notifyListeners();
  }

  void clearError() {
    _error = null;
    notifyListeners();
  }
}
