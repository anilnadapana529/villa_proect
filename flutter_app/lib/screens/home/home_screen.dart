import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/auth_provider.dart';
import '../../providers/villa_provider.dart';
import '../../widgets/villa_card.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final TextEditingController _searchController = TextEditingController();
  final PageController _bannerController = PageController();
  int _currentBannerIndex = 0;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<VillaProvider>(context, listen: false).fetchVillas();
    });
  }

  @override
  void dispose() {
    _searchController.dispose();
    _bannerController.dispose();
    super.dispose();
  }

  void _handleSearch() {
    if (_searchController.text.trim().isNotEmpty) {
      Navigator.pushNamed(
        context,
        '/villas',
        arguments: {'search': _searchController.text.trim()},
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);
    final villaProvider = Provider.of<VillaProvider>(context);

    final allVillas = villaProvider.villas;
    final popularVillas = allVillas.take(6).toList();
    final featuredVillas = allVillas.where((v) => v.rating != null && v.rating! >= 4.0).take(3).toList();

    return Scaffold(
      body: RefreshIndicator(
        onRefresh: () => villaProvider.fetchVillas(),
        child: CustomScrollView(
          slivers: [
            _buildAppBar(authProvider),
            SliverToBoxAdapter(
              child: Column(
                children: [
                  if (villaProvider.isLoading)
                    const Padding(
                      padding: EdgeInsets.all(48),
                      child: CircularProgressIndicator(),
                    )
                  else if (villaProvider.error != null)
                    Padding(
                      padding: const EdgeInsets.all(32),
                      child: Column(
                        children: [
                          const Icon(Icons.error_outline, size: 64, color: Colors.red),
                          const SizedBox(height: 16),
                          Text(
                            villaProvider.error!,
                            style: const TextStyle(color: Colors.red),
                            textAlign: TextAlign.center,
                          ),
                          const SizedBox(height: 16),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              ElevatedButton(
                                onPressed: () => villaProvider.fetchVillas(),
                                child: const Text('Retry'),
                              ),
                              const SizedBox(width: 16),
                              ElevatedButton(
                                onPressed: () => Navigator.pushNamed(context, '/debug'),
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: Colors.orange,
                                ),
                                child: const Text('Debug API'),
                              ),
                            ],
                          ),
                        ],
                      ),
                    )
                  else ...[
                    _buildPromoBanner(),
                    const SizedBox(height: 32),
                    _buildQuickStats(allVillas.length),
                    const SizedBox(height: 32),
                    _buildFeaturedSection(featuredVillas),
                    const SizedBox(height: 32),
                    _buildPopularSection(popularVillas),
                    const SizedBox(height: 32),
                    _buildWhyChooseUs(),
                    const SizedBox(height: 32),
                    _buildTestimonials(),
                    const SizedBox(height: 32),
                    _buildCTA(authProvider),
                    const SizedBox(height: 48),
                  ],
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildAppBar(AuthProvider authProvider) {
    return SliverAppBar(
      expandedHeight: 280,
      floating: false,
      pinned: true,
      flexibleSpace: FlexibleSpaceBar(
        background: Container(
          decoration: const BoxDecoration(
            gradient: LinearGradient(
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
              colors: [
                Color(0xFF1E3A8A),
                Color(0xFF3B82F6),
                Color(0xFF60A5FA),
              ],
            ),
          ),
          child: SafeArea(
            child: Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.end,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    authProvider.isAuthenticated
                        ? 'Welcome back, ${authProvider.user?.name.split(' ').first}!'
                        : 'Find Your Dream Villa',
                    style: const TextStyle(
                      fontSize: 32,
                      fontWeight: FontWeight.bold,
                      color: Colors.white,
                    ),
                  ),
                  const SizedBox(height: 8),
                  const Text(
                    'Luxury stays in the most beautiful destinations',
                    style: TextStyle(
                      fontSize: 16,
                      color: Colors.white70,
                    ),
                  ),
                  const SizedBox(height: 24),
                  TextField(
                    controller: _searchController,
                    onSubmitted: (_) => _handleSearch(),
                    style: const TextStyle(color: Colors.black87),
                    decoration: InputDecoration(
                      hintText: 'Where do you want to go?',
                      filled: true,
                      fillColor: Colors.white,
                      prefixIcon: const Icon(Icons.search, color: Color(0xFF1E3A8A)),
                      suffixIcon: IconButton(
                        icon: const Icon(Icons.arrow_forward, color: Color(0xFF1E3A8A)),
                        onPressed: _handleSearch,
                      ),
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(30),
                        borderSide: BorderSide.none,
                      ),
                      contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
      actions: [
        if (authProvider.isAuthenticated)
          IconButton(
            icon: const Icon(Icons.person),
            onPressed: () {
              final role = authProvider.role;
              if (role == 'admin') {
                Navigator.pushNamed(context, '/admin-dashboard');
              } else if (role == 'owner') {
                Navigator.pushNamed(context, '/owner-dashboard');
              } else {
                Navigator.pushNamed(context, '/user-dashboard');
              }
            },
          )
        else
          Padding(
            padding: const EdgeInsets.only(right: 8),
            child: Row(
              children: [
                TextButton(
                  onPressed: () => Navigator.pushNamed(context, '/login'),
                  style: TextButton.styleFrom(foregroundColor: Colors.white),
                  child: const Text('Login'),
                ),
                const SizedBox(width: 8),
                ElevatedButton(
                  onPressed: () => Navigator.pushNamed(context, '/register'),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.white,
                    foregroundColor: const Color(0xFF1E3A8A),
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  ),
                  child: const Text('Sign Up'),
                ),
              ],
            ),
          ),
      ],
    );
  }

  Widget _buildPromoBanner() {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16),
      height: 180,
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFFEF4444), Color(0xFFF59E0B)],
        ),
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: Colors.orange.withOpacity(0.3),
            blurRadius: 15,
            offset: const Offset(0, 8),
          ),
        ],
      ),
      child: Stack(
        children: [
          Positioned(
            right: -20,
            top: -20,
            child: Icon(
              Icons.villa,
              size: 180,
              color: Colors.white.withOpacity(0.1),
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(24),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                const Text(
                  '🎉 Special Offer!',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 28,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 8),
                const Text(
                  'Get 20% OFF on your first booking',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 16,
                  ),
                ),
                const SizedBox(height: 16),
                ElevatedButton(
                  onPressed: () => Navigator.pushNamed(context, '/villas'),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.white,
                    foregroundColor: const Color(0xFFEF4444),
                    padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                  ),
                  child: const Text('Book Now'),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildQuickStats(int totalVillas) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Row(
        children: [
          Expanded(
            child: _buildStatCard(
              icon: Icons.villa,
              count: '$totalVillas+',
              label: 'Properties',
              color: const Color(0xFF3B82F6),
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: _buildStatCard(
              icon: Icons.verified,
              count: '100%',
              label: 'Verified',
              color: const Color(0xFF059669),
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: _buildStatCard(
              icon: Icons.star,
              count: '4.8',
              label: 'Rating',
              color: const Color(0xFFF59E0B),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStatCard({
    required IconData icon,
    required String count,
    required String label,
    required Color color,
  }) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: color.withOpacity(0.3)),
      ),
      child: Column(
        children: [
          Icon(icon, color: color, size: 32),
          const SizedBox(height: 8),
          Text(
            count,
            style: TextStyle(
              fontSize: 24,
              fontWeight: FontWeight.bold,
              color: color,
            ),
          ),
          Text(
            label,
            style: TextStyle(
              fontSize: 12,
              color: Colors.grey.shade600,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildFeaturedSection(List villas) {
    if (villas.isEmpty) return const SizedBox.shrink();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    '⭐ Featured Properties',
                    style: TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                      color: Color(0xFF1E3A8A),
                    ),
                  ),
                  SizedBox(height: 4),
                  Text(
                    'Handpicked luxury villas for you',
                    style: TextStyle(color: Colors.grey),
                  ),
                ],
              ),
            ],
          ),
        ),
        const SizedBox(height: 16),
        SizedBox(
          height: 280,
          child: ListView.builder(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.symmetric(horizontal: 16),
            itemCount: villas.length,
            itemBuilder: (context, index) {
              return Container(
                width: 300,
                margin: const EdgeInsets.only(right: 16),
                child: VillaCard(villa: villas[index]),
              );
            },
          ),
        ),
      ],
    );
  }

  Widget _buildPopularSection(List villas) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    '🔥 Popular Villas',
                    style: TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                      color: Color(0xFF1E3A8A),
                    ),
                  ),
                  SizedBox(height: 4),
                  Text(
                    'Most booked properties this month',
                    style: TextStyle(color: Colors.grey),
                  ),
                ],
              ),
              TextButton(
                onPressed: () => Navigator.pushNamed(context, '/villas'),
                child: const Text('View All →'),
              ),
            ],
          ),
        ),
        const SizedBox(height: 16),
        if (villas.isEmpty)
          const Padding(
            padding: EdgeInsets.all(48),
            child: Center(
              child: Column(
                children: [
                  Icon(Icons.villa_outlined, size: 80, color: Colors.grey),
                  SizedBox(height: 16),
                  Text(
                    'No villas available yet',
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.w600),
                  ),
                  SizedBox(height: 8),
                  Text(
                    'Check back soon for amazing properties!',
                    style: TextStyle(color: Colors.grey),
                  ),
                ],
              ),
            ),
          )
        else
          ListView.builder(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            padding: const EdgeInsets.symmetric(horizontal: 16),
            itemCount: villas.length,
            itemBuilder: (context, index) {
              return VillaCard(villa: villas[index]);
            },
          ),
      ],
    );
  }

  Widget _buildWhyChooseUs() {
    return Container(
      color: Colors.grey.shade50,
      padding: const EdgeInsets.symmetric(vertical: 48, horizontal: 16),
      child: Column(
        children: [
          const Text(
            'Why Choose Us?',
            style: TextStyle(
              fontSize: 28,
              fontWeight: FontWeight.bold,
              color: Color(0xFF1E3A8A),
            ),
          ),
          const SizedBox(height: 32),
          _buildFeatureItem(
            Icons.verified_user,
            'Verified Properties',
            'All villas are verified and inspected',
            const Color(0xFF059669),
          ),
          const SizedBox(height: 24),
          _buildFeatureItem(
            Icons.support_agent,
            '24/7 Support',
            'Round the clock customer assistance',
            const Color(0xFF3B82F6),
          ),
          const SizedBox(height: 24),
          _buildFeatureItem(
            Icons.price_check,
            'Best Price Guarantee',
            'Competitive prices with no hidden fees',
            const Color(0xFFF59E0B),
          ),
          const SizedBox(height: 24),
          _buildFeatureItem(
            Icons.lock,
            'Secure Booking',
            'Safe and secure payment processing',
            const Color(0xFFEF4444),
          ),
        ],
      ),
    );
  }

  Widget _buildFeatureItem(IconData icon, String title, String subtitle, Color color) {
    return Row(
      children: [
        Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: color.withOpacity(0.1),
            borderRadius: BorderRadius.circular(16),
          ),
          child: Icon(icon, color: color, size: 32),
        ),
        const SizedBox(width: 16),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                title,
                style: const TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 4),
              Text(
                subtitle,
                style: TextStyle(color: Colors.grey.shade600),
              ),
            ],
          ),
        ),
      ],
    );
  }

  Widget _buildTestimonials() {
    final testimonials = [
      {
        'name': 'Sarah Johnson',
        'rating': 5,
        'text': 'Amazing experience! The villa was exactly as shown. Beautiful property and excellent service.',
        'image': '👩‍💼',
      },
      {
        'name': 'Michael Chen',
        'rating': 5,
        'text': 'Perfect vacation spot. The booking process was smooth and the property exceeded expectations.',
        'image': '👨‍💻',
      },
      {
        'name': 'Emily Rodriguez',
        'rating': 5,
        'text': 'Highly recommend! Great value for money and stunning location. Will definitely book again.',
        'image': '👩‍🎨',
      },
    ];

    return Column(
      children: [
        const Text(
          '💬 What Our Guests Say',
          style: TextStyle(
            fontSize: 28,
            fontWeight: FontWeight.bold,
            color: Color(0xFF1E3A8A),
          ),
        ),
        const SizedBox(height: 24),
        SizedBox(
          height: 200,
          child: ListView.builder(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.symmetric(horizontal: 16),
            itemCount: testimonials.length,
            itemBuilder: (context, index) {
              final testimonial = testimonials[index];
              return Container(
                width: 320,
                margin: const EdgeInsets.only(right: 16),
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(16),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.grey.shade200,
                      blurRadius: 10,
                      offset: const Offset(0, 4),
                    ),
                  ],
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Text(
                          testimonial['image'] as String,
                          style: const TextStyle(fontSize: 40),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                testimonial['name'] as String,
                                style: const TextStyle(
                                  fontWeight: FontWeight.bold,
                                  fontSize: 16,
                                ),
                              ),
                              Row(
                                children: List.generate(
                                  5,
                                  (i) => const Icon(
                                    Icons.star,
                                    color: Color(0xFFF59E0B),
                                    size: 16,
                                  ),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 16),
                    Text(
                      testimonial['text'] as String,
                      style: TextStyle(
                        color: Colors.grey.shade700,
                        height: 1.5,
                      ),
                      maxLines: 4,
                      overflow: TextOverflow.ellipsis,
                    ),
                  ],
                ),
              );
            },
          ),
        ),
      ],
    );
  }

  Widget _buildCTA(AuthProvider authProvider) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16),
      padding: const EdgeInsets.all(32),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFF1E3A8A), Color(0xFF3B82F6)],
        ),
        borderRadius: BorderRadius.circular(20),
      ),
      child: Column(
        children: [
          const Text(
            'Ready to Book Your Dream Villa?',
            style: TextStyle(
              color: Colors.white,
              fontSize: 24,
              fontWeight: FontWeight.bold,
            ),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 16),
          const Text(
            'Join thousands of satisfied guests and experience luxury like never before',
            style: TextStyle(
              color: Colors.white70,
              fontSize: 16,
            ),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 24),
          ElevatedButton(
            onPressed: () {
              if (authProvider.isAuthenticated) {
                Navigator.pushNamed(context, '/villas');
              } else {
                Navigator.pushNamed(context, '/register');
              }
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.white,
              foregroundColor: const Color(0xFF1E3A8A),
              padding: const EdgeInsets.symmetric(horizontal: 48, vertical: 16),
              textStyle: const TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
              ),
            ),
            child: Text(authProvider.isAuthenticated ? 'Browse Villas' : 'Get Started'),
          ),
        ],
      ),
    );
  }
}
