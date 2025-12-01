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

    try {
      final url = 'https://topmost.in/api/villas';
      _addLog('URL: $url');
      _addLog('Making request...');

      final response = await http.get(
        Uri.parse(url),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 10));

      _addLog('Response received!');
      _addLog('Status Code: ${response.statusCode}');
      _addLog('Body Length: ${response.body.length}');

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        _addLog('Keys: ${data.keys.join(", ")}');

        if (data['villas'] != null) {
          final villas = data['villas'] as List;
          _addLog('✅ SUCCESS!');
          _addLog('Found ${villas.length} villas');
          _addLog('First villa: ${villas.first['name']}');
        } else {
          _addLog('❌ No villas key in response');
        }
      } else {
        _addLog('❌ HTTP Error: ${response.statusCode}');
        _addLog('Body: ${response.body}');
      }
    } catch (e, stack) {
      _addLog('❌ EXCEPTION: $e');
      _addLog('Stack: ${stack.toString().substring(0, 200)}...');
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
