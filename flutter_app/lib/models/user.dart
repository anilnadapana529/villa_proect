class User {
  final int id;
  final String name;
  final String email;
  final String phone;
  final String? profileImage;
  final String? idProof;
  final String kycStatus;
  final double walletBalance;
  final String status;
  final DateTime createdAt;
  final DateTime updatedAt;

  User({
    required this.id,
    required this.name,
    required this.email,
    required this.phone,
    this.profileImage,
    this.idProof,
    required this.kycStatus,
    required this.walletBalance,
    required this.status,
    required this.createdAt,
    required this.updatedAt,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      email: json['email'] ?? '',
      phone: json['phone'] ?? '',
      profileImage: json['profile_image'],
      idProof: json['id_proof'],
      kycStatus: json['kyc_status'] ?? 'pending',
      walletBalance: double.tryParse(json['wallet_balance']?.toString() ?? '0') ?? 0.0,
      status: json['status'] ?? 'active',
      createdAt: DateTime.parse(json['created_at'] ?? DateTime.now().toIso8601String()),
      updatedAt: DateTime.parse(json['updated_at'] ?? DateTime.now().toIso8601String()),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'phone': phone,
      'profile_image': profileImage,
      'id_proof': idProof,
      'kyc_status': kycStatus,
      'wallet_balance': walletBalance.toString(),
      'status': status,
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
    };
  }
}
