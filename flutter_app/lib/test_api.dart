import 'dart:convert';
import 'package:http/http.dart' as http;

void main() async {
  print('Testing API connection...');
  print('URL: https://topmost.in/api/villas');

  try {
    final response = await http.get(
      Uri.parse('https://topmost.in/api/villas'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
    );

    print('Status Code: ${response.statusCode}');
    print('Response Length: ${response.body.length}');

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      print('Success! Keys: ${data.keys}');

      if (data['villas'] != null) {
        print('Number of villas: ${(data['villas'] as List).length}');
        print('First villa: ${(data['villas'] as List).first}');
      }
    } else {
      print('Error: ${response.body}');
    }
  } catch (e) {
    print('Exception: $e');
  }
}
