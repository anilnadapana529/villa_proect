class Booking {
  final int id;
  final int userId;
  final int villaId;
  final int ownerId;
  final DateTime checkIn;
  final DateTime checkOut;
  final int guests;
  final double totalPrice;
  final String status;
  final String? paymentStatus;
  final String? paymentId;
  final DateTime createdAt;
  final DateTime updatedAt;
  final String? villaName;
  final String? villaImage;

  Booking({
    required this.id,
    required this.userId,
    required this.villaId,
    required this.ownerId,
    required this.checkIn,
    required this.checkOut,
    required this.guests,
    required this.totalPrice,
    required this.status,
    this.paymentStatus,
    this.paymentId,
    required this.createdAt,
    required this.updatedAt,
    this.villaName,
    this.villaImage,
  });

  factory Booking.fromJson(Map<String, dynamic> json) {
    return Booking(
      id: json['id'] ?? 0,
      userId: json['user_id'] ?? 0,
      villaId: json['villa_id'] ?? 0,
      ownerId: json['owner_id'] ?? 0,
      checkIn: DateTime.parse(json['check_in'] ?? DateTime.now().toIso8601String()),
      checkOut: DateTime.parse(json['check_out'] ?? DateTime.now().toIso8601String()),
      guests: json['guests'] ?? 1,
      totalPrice: double.tryParse(json['total_price']?.toString() ?? '0') ?? 0.0,
      status: json['status'] ?? 'pending',
      paymentStatus: json['payment_status'],
      paymentId: json['payment_id'],
      createdAt: DateTime.parse(json['created_at'] ?? DateTime.now().toIso8601String()),
      updatedAt: DateTime.parse(json['updated_at'] ?? DateTime.now().toIso8601String()),
      villaName: json['villa_name'],
      villaImage: json['image'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'user_id': userId,
      'villa_id': villaId,
      'owner_id': ownerId,
      'check_in': checkIn.toIso8601String(),
      'check_out': checkOut.toIso8601String(),
      'guests': guests,
      'total_price': totalPrice.toString(),
      'status': status,
      'payment_status': paymentStatus,
      'payment_id': paymentId,
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
      'villa_name': villaName,
      'image': villaImage,
    };
  }

  int get nights {
    return checkOut.difference(checkIn).inDays;
  }
}
