class Villa {
  final int id;
  final int ownerId;
  final String title;
  final String description;
  final String location;
  final double pricePerNight;
  final int bedrooms;
  final int bathrooms;
  final int maxGuests;
  final String? image;
  final List<String> images;
  final String amenities;
  final String status;
  final double? rating;
  final DateTime createdAt;
  final DateTime updatedAt;
  final String? ownerName;

  Villa({
    required this.id,
    required this.ownerId,
    required this.title,
    required this.description,
    required this.location,
    required this.pricePerNight,
    required this.bedrooms,
    required this.bathrooms,
    required this.maxGuests,
    this.image,
    this.images = const [],
    required this.amenities,
    required this.status,
    this.rating,
    required this.createdAt,
    required this.updatedAt,
    this.ownerName,
  });

  factory Villa.fromJson(Map<String, dynamic> json) {
    List<String> imagesList = [];
    if (json['images'] != null) {
      if (json['images'] is String) {
        imagesList = (json['images'] as String).split(',').map((e) => e.trim()).toList();
      } else if (json['images'] is List) {
        imagesList = List<String>.from(json['images']);
      }
    }

    return Villa(
      id: json['id'] ?? 0,
      ownerId: json['owner_id'] ?? 0,
      title: json['title'] ?? '',
      description: json['description'] ?? '',
      location: json['location'] ?? '',
      pricePerNight: double.tryParse(json['price_per_night']?.toString() ?? '0') ?? 0.0,
      bedrooms: json['bedrooms'] ?? 0,
      bathrooms: json['bathrooms'] ?? 0,
      maxGuests: json['max_guests'] ?? 0,
      image: json['image'],
      images: imagesList,
      amenities: json['amenities'] ?? '',
      status: json['status'] ?? 'pending',
      rating: json['rating'] != null ? double.tryParse(json['rating'].toString()) : null,
      createdAt: DateTime.parse(json['created_at'] ?? DateTime.now().toIso8601String()),
      updatedAt: DateTime.parse(json['updated_at'] ?? DateTime.now().toIso8601String()),
      ownerName: json['owner_name'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'owner_id': ownerId,
      'title': title,
      'description': description,
      'location': location,
      'price_per_night': pricePerNight.toString(),
      'bedrooms': bedrooms,
      'bathrooms': bathrooms,
      'max_guests': maxGuests,
      'image': image,
      'images': images.join(','),
      'amenities': amenities,
      'status': status,
      'rating': rating?.toString(),
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
      'owner_name': ownerName,
    };
  }

  String get mainImage {
    if (image != null && image!.isNotEmpty) {
      return image!.startsWith('http') ? image! : 'https://topmost.in/uploads/villas/$image';
    }
    if (images.isNotEmpty) {
      return images[0].startsWith('http') ? images[0] : 'https://topmost.in/uploads/villas/${images[0]}';
    }
    return 'https://via.placeholder.com/400x300';
  }

  List<String> get amenitiesList {
    return amenities.split(',').map((e) => e.trim()).toList();
  }
}
