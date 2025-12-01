import 'package:flutter/material.dart';
import '../models/villa.dart';
import '../services/api_service.dart';

class VillaProvider with ChangeNotifier {
  List<Villa> _villas = [];
  Villa? _selectedVilla;
  bool _isLoading = false;
  String? _error;

  List<Villa> get villas => _villas;
  Villa? get selectedVilla => _selectedVilla;
  bool get isLoading => _isLoading;
  String? get error => _error;

  Future<void> fetchVillas() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      print('VillaProvider: Starting fetch villas...');
      _villas = await ApiService.getVillas();
      print('VillaProvider: Received ${_villas.length} villas');

      if (_villas.isEmpty) {
        _error = 'No villas available at the moment';
      }

      _isLoading = false;
      notifyListeners();
    } catch (e) {
      print('VillaProvider: Error - $e');
      _error = 'Failed to load villas: $e';
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> fetchVillaById(int id) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      _selectedVilla = await ApiService.getVillaById(id);
      _isLoading = false;
      notifyListeners();
    } catch (e) {
      _error = 'Failed to load villa details: $e';
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> searchVillas({
    String? location,
    int? guests,
    DateTime? checkIn,
    DateTime? checkOut,
  }) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      _villas = await ApiService.searchVillas(
        location: location,
        guests: guests,
        checkIn: checkIn,
        checkOut: checkOut,
      );
      _isLoading = false;
      notifyListeners();
    } catch (e) {
      _error = 'Failed to search villas: $e';
      _isLoading = false;
      notifyListeners();
    }
  }

  void selectVilla(Villa villa) {
    _selectedVilla = villa;
    notifyListeners();
  }

  void clearError() {
    _error = null;
    notifyListeners();
  }
}
