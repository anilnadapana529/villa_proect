import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;

class DebugScreen extends StatefulWidget {
  const DebugScreen({super.key});

  @override
  State<DebugScreen> createState() => _DebugScreenState();
}

class _DebugScreenState extends State<DebugScreen> {
  String _status = 'Ready to test';
  bool _isLoading = false;

  Future<void> _testAPI() async {
    setState(() {
      _isLoading = true;
      _status = 'Testing API...\n\n';
    });

    // Test 1: Simple connectivity
    _addLog('TEST 1: Testing basic connectivity...');
    try {
      final testUrl = 'https://www.google.com';
      _addLog('Trying to reach: $testUrl');
      final testResponse = await http.get(Uri.parse(testUrl)).timeout(
        const Duration(seconds: 5),
        onTimeout: () => throw Exception('Google timeout'),
      );
      _addLog('✅ Internet works (Google: ${testResponse.statusCode})');
    } catch (e) {
      _addLog('❌ No internet connection: $e');
      _addLog('');
      _addLog('FIX: Enable internet on your device/emulator');
      setState(() => _isLoading = false);
      return;
    }

    _addLog('');

    // Test 2: API endpoint
    _addLog('TEST 2: Testing API endpoint...');
    try {
      final url = 'https://topmost.in/api/villas';
      _addLog('URL: $url');
      _addLog('Making request (30s timeout)...');

      final response = await http.get(
        Uri.parse(url),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(
        const Duration(seconds: 30),
        onTimeout: () => throw Exception('API timeout after 30s'),
      );

      _addLog('Response received!');
      _addLog('Status Code: ${response.statusCode}');
      _addLog('Body Length: ${response.body.length} bytes');

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        _addLog('Keys: ${data.keys.join(", ")}');

        if (data['villas'] != null) {
          final villas = data['villas'] as List;
          _addLog('');
          _addLog('✅✅✅ SUCCESS! ✅✅✅');
          _addLog('Found ${villas.length} villas');
          if (villas.isNotEmpty) {
            _addLog('First villa: ${villas.first['name']}');
            _addLog('Price: ${villas.first['price']}');
          }
        } else {
          _addLog('❌ No villas key in response');
          _addLog('Response: ${response.body}');
        }
      } else {
        _addLog('❌ HTTP Error: ${response.statusCode}');
        _addLog('Body: ${response.body}');
      }
    } catch (e, stack) {
      _addLog('❌ EXCEPTION: $e');
      if (e.toString().contains('timeout')) {
        _addLog('');
        _addLog('FIX: Network too slow or blocked');
        _addLog('Try using WiFi instead of mobile data');
      }
      final stackStr = stack.toString();
      _addLog('Stack: ${stackStr.substring(0, stackStr.length > 200 ? 200 : stackStr.length)}...');
    }

    setState(() {
      _isLoading = false;
    });
  }

  void _addLog(String message) {
    setState(() {
      _status += '$message\n';
    });
    print(message);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('API Debug'),
        backgroundColor: Colors.blue,
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: ElevatedButton.icon(
              onPressed: _isLoading ? null : _testAPI,
              icon: _isLoading
                  ? const SizedBox(
                      width: 20,
                      height: 20,
                      child: CircularProgressIndicator(strokeWidth: 2),
                    )
                  : const Icon(Icons.play_arrow),
              label: Text(_isLoading ? 'Testing...' : 'Test API'),
              style: ElevatedButton.styleFrom(
                padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 16),
              ),
            ),
          ),
          Expanded(
            child: Container(
              margin: const EdgeInsets.all(16),
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.black87,
                borderRadius: BorderRadius.circular(8),
              ),
              child: SingleChildScrollView(
                child: Text(
                  _status,
                  style: const TextStyle(
                    color: Colors.greenAccent,
                    fontFamily: 'monospace',
                    fontSize: 12,
                  ),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
