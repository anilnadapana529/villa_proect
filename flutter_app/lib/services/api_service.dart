import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../models/user.dart';
import '../models/villa.dart';
import '../models/booking.dart';

class ApiService {
  static const String baseUrl = 'https://topmost.in/api';

  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('token');
  }

  static Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('token', token);
  }

  static Future<void> removeToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('token');
    await prefs.remove('role');
    await prefs.remove('user');
  }

  static Future<String?> getRole() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('role');
  }

  static Future<void> saveRole(String role) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('role', role);
  }

  static Future<Map<String, String>> getHeaders({bool includeAuth = false}) async {
    final headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };

    if (includeAuth) {
      final token = await getToken();
      if (token != null) {
        headers['Authorization'] = 'Bearer $token';
      }
    }

    return headers;
  }

  static Future<Map<String, dynamic>> login({
    required String email,
    required String password,
    required String role,
  }) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/$role-login'),
        headers: await getHeaders(),
        body: jsonEncode({
          'email': email,
          'password': password,
        }),
      );

      final data = jsonDecode(response.body);

      if (response.statusCode == 200 && data['status'] == true) {
        await saveToken(data['token']);
        await saveRole(role);
        return {'success': true, 'data': data};
      } else {
        return {'success': false, 'message': data['message'] ?? 'Login failed'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Network error: $e'};
    }
  }

  static Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String phone,
    required String password,
  }) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/user-register'),
        headers: await getHeaders(),
        body: jsonEncode({
          'name': name,
          'email': email,
          'phone': phone,
          'password': password,
        }),
      );

      final data = jsonDecode(response.body);

      if (response.statusCode == 200 && data['status'] == true) {
        await saveToken(data['token']);
        await saveRole('user');
        return {'success': true, 'data': data};
      } else {
        return {'success': false, 'message': data['message'] ?? 'Registration failed'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Network error: $e'};
    }
  }

  static Future<List<Villa>> getVillas() async {
    try {
      print('Fetching villas from: $baseUrl/villas');
      final response = await http.get(
        Uri.parse('$baseUrl/villas'),
        headers: await getHeaders(includeAuth: true),
      ).timeout(const Duration(seconds: 15));

      print('Response status: ${response.statusCode}');
      print('Response body length: ${response.body.length}');

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        print('Response data keys: ${data.keys}');

        if (data['villas'] != null) {
          print('Villas array length: ${(data['villas'] as List).length}');
          final villas = (data['villas'] as List)
              .map((json) {
                print('Parsing villa: ${json['id']} - ${json['name']}');
                return Villa.fromJson(json);
              })
              .toList();
          print('Successfully fetched ${villas.length} villas');
          return villas;
        } else {
          print('ERROR: No villas key in response');
        }
      } else {
        print('ERROR: Failed to fetch villas: ${response.statusCode}');
      }
      return [];
    } catch (e, stackTrace) {
      print('ERROR fetching villas: $e');
      print('Stack trace: $stackTrace');
      return [];
    }
  }

  static Future<Villa?> getVillaById(int id) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/villas/$id'),
        headers: await getHeaders(includeAuth: true),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['villa'] != null) {
          return Villa.fromJson(data['villa']);
        }
      }
      return null;
    } catch (e) {
      print('Error fetching villa by ID: $e');
      return null;
    }
  }

  static Future<List<Booking>> getUserBookings() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/user/bookings'),
        headers: await getHeaders(includeAuth: true),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['bookings'] != null) {
          return (data['bookings'] as List)
              .map((json) => Booking.fromJson(json))
              .toList();
        }
      }
      return [];
    } catch (e) {
      return [];
    }
  }

  static Future<Map<String, dynamic>> createBooking({
    required int villaId,
    required DateTime checkIn,
    required DateTime checkOut,
    required int guests,
    required double totalPrice,
  }) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/bookings'),
        headers: await getHeaders(includeAuth: true),
        body: jsonEncode({
          'villa_id': villaId,
          'check_in': checkIn.toIso8601String(),
          'check_out': checkOut.toIso8601String(),
          'guests': guests,
          'total_price': totalPrice.toString(),
        }),
      );

      final data = jsonDecode(response.body);

      if (response.statusCode == 200 && data['status'] == true) {
        return {'success': true, 'data': data};
      } else {
        return {'success': false, 'message': data['message'] ?? 'Booking failed'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Network error: $e'};
    }
  }

  static Future<List<Villa>> searchVillas({
    String? location,
    int? guests,
    DateTime? checkIn,
    DateTime? checkOut,
  }) async {
    try {
      final queryParams = <String, String>{};
      if (location != null && location.isNotEmpty) queryParams['location'] = location;
      if (guests != null) queryParams['guests'] = guests.toString();
      if (checkIn != null) queryParams['check_in'] = checkIn.toIso8601String();
      if (checkOut != null) queryParams['check_out'] = checkOut.toIso8601String();

      final uri = Uri.parse('$baseUrl/search').replace(queryParameters: queryParams);
      final response = await http.get(
        uri,
        headers: await getHeaders(includeAuth: true),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['villas'] != null) {
          return (data['villas'] as List)
              .map((json) => Villa.fromJson(json))
              .toList();
        }
      }
      return [];
    } catch (e) {
      print('Error searching villas: $e');
      return [];
    }
  }

  static Future<List<Villa>> getOwnerVillas() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/owner/villas'),
        headers: await getHeaders(includeAuth: true),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['villas'] != null) {
          return (data['villas'] as List)
              .map((json) => Villa.fromJson(json))
              .toList();
        }
      }
      return [];
    } catch (e) {
      return [];
    }
  }

  static Future<Map<String, dynamic>> createVilla(Map<String, dynamic> villaData) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/villas'),
        headers: await getHeaders(includeAuth: true),
        body: jsonEncode(villaData),
      );

      final data = jsonDecode(response.body);

      if (response.statusCode == 200 && data['status'] == true) {
        return {'success': true, 'data': data};
      } else {
        return {'success': false, 'message': data['message'] ?? 'Failed to create villa'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Network error: $e'};
    }
  }

  static Future<Map<String, dynamic>> updateVilla(int id, Map<String, dynamic> villaData) async {
    try {
      final response = await http.put(
        Uri.parse('$baseUrl/villas/$id'),
        headers: await getHeaders(includeAuth: true),
        body: jsonEncode(villaData),
      );

      final data = jsonDecode(response.body);

      if (response.statusCode == 200 && data['status'] == true) {
        return {'success': true, 'data': data};
      } else {
        return {'success': false, 'message': data['message'] ?? 'Failed to update villa'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Network error: $e'};
    }
  }

  static Future<Map<String, dynamic>> deleteVilla(int id) async {
    try {
      final response = await http.delete(
        Uri.parse('$baseUrl/villas/$id'),
        headers: await getHeaders(includeAuth: true),
      );

      final data = jsonDecode(response.body);

      if (response.statusCode == 200 && data['status'] == true) {
        return {'success': true};
      } else {
        return {'success': false, 'message': data['message'] ?? 'Failed to delete villa'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Network error: $e'};
    }
  }

  static Future<Map<String, dynamic>> getOwnerDashboard() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/owner/dashboard'),
        headers: await getHeaders(includeAuth: true),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return {'success': true, 'data': data};
      } else {
        return {'success': false, 'message': 'Failed to load dashboard'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Network error: $e'};
    }
  }

  static Future<List<Villa>> getAdminVillas() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/admin/villas'),
        headers: await getHeaders(includeAuth: true),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['villas'] != null) {
          return (data['villas'] as List)
              .map((json) => Villa.fromJson(json))
              .toList();
        }
      }
      return [];
    } catch (e) {
      return [];
    }
  }

  static Future<List<Villa>> getPendingVillas() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/admin/pending-villas'),
        headers: await getHeaders(includeAuth: true),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['pending_villas'] != null) {
          return (data['pending_villas'] as List)
              .map((json) => Villa.fromJson(json))
              .toList();
        }
      }
      return [];
    } catch (e) {
      return [];
    }
  }

  static Future<Map<String, dynamic>> approveVilla(int id) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/admin/villa/approve?id=$id'),
        headers: await getHeaders(includeAuth: true),
      );

      final data = jsonDecode(response.body);

      if (response.statusCode == 200 && data['status'] == true) {
        return {'success': true};
      } else {
        return {'success': false, 'message': 'Failed to approve villa'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Network error: $e'};
    }
  }

  static Future<Map<String, dynamic>> rejectVilla(int id) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/admin/villa/reject?id=$id'),
        headers: await getHeaders(includeAuth: true),
      );

      final data = jsonDecode(response.body);

      if (response.statusCode == 200 && data['status'] == true) {
        return {'success': true};
      } else {
        return {'success': false, 'message': 'Failed to reject villa'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Network error: $e'};
    }
  }

  static Future<Map<String, dynamic>> getAdminDashboard() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/admin/dashboard'),
        headers: await getHeaders(includeAuth: true),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return {'success': true, 'data': data};
      } else {
        return {'success': false, 'message': 'Failed to load dashboard'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Network error: $e'};
    }
  }
}
