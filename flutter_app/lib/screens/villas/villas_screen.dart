import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/villa_provider.dart';
import '../../widgets/villa_card.dart';

class VillasScreen extends StatefulWidget {
  const VillasScreen({super.key});

  @override
  State<VillasScreen> createState() => _VillasScreenState();
}

class _VillasScreenState extends State<VillasScreen> {
  final _searchController = TextEditingController();
  String _sortBy = 'name';
  double _minPrice = 0;
  double _maxPrice = 100000;
  int? _filterGuests;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final args = ModalRoute.of(context)?.settings.arguments as Map<String, dynamic>?;
      if (args != null && args['search'] != null) {
        _searchController.text = args['search'];
        Provider.of<VillaProvider>(context, listen: false).searchVillas(
          location: args['search'],
        );
      } else {
        Provider.of<VillaProvider>(context, listen: false).fetchVillas();
      }
    });
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  void _handleSearch() {
    final villaProvider = Provider.of<VillaProvider>(context, listen: false);
    villaProvider.searchVillas(
      location: _searchController.text.trim(),
      guests: _filterGuests,
    );
  }

  void _showFilters() {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => StatefulBuilder(
        builder: (context, setModalState) => Container(
          decoration: const BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
          ),
          padding: EdgeInsets.only(
            bottom: MediaQuery.of(context).viewInsets.bottom,
          ),
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(24),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisSize: MainAxisSize.min,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text(
                      'Filters',
                      style: TextStyle(
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    IconButton(
                      icon: const Icon(Icons.close),
                      onPressed: () => Navigator.pop(context),
                    ),
                  ],
                ),
                const SizedBox(height: 24),
                const Text(
                  'Price Range',
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.w600,
                  ),
                ),
                const SizedBox(height: 8),
                RangeSlider(
                  values: RangeValues(_minPrice, _maxPrice),
                  min: 0,
                  max: 100000,
                  divisions: 100,
                  labels: RangeLabels(
                    '₹${_minPrice.toInt()}',
                    '₹${_maxPrice.toInt()}',
                  ),
                  onChanged: (values) {
                    setModalState(() {
                      _minPrice = values.start;
                      _maxPrice = values.end;
                    });
                  },
                ),
                Text(
                  '₹${_minPrice.toInt()} - ₹${_maxPrice.toInt()}',
                  style: const TextStyle(color: Colors.grey),
                ),
                const SizedBox(height: 24),
                const Text(
                  'Guests',
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.w600,
                  ),
                ),
                const SizedBox(height: 8),
                Wrap(
                  spacing: 8,
                  children: [
                    for (int i = 1; i <= 10; i++)
                      ChoiceChip(
                        label: Text('$i'),
                        selected: _filterGuests == i,
                        onSelected: (selected) {
                          setModalState(() {
                            _filterGuests = selected ? i : null;
                          });
                        },
                      ),
                  ],
                ),
                const SizedBox(height: 24),
                const Text(
                  'Sort By',
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.w600,
                  ),
                ),
                const SizedBox(height: 8),
                Wrap(
                  spacing: 8,
                  children: [
                    ChoiceChip(
                      label: const Text('Name'),
                      selected: _sortBy == 'name',
                      onSelected: (selected) {
                        setModalState(() => _sortBy = 'name');
                      },
                    ),
                    ChoiceChip(
                      label: const Text('Price: Low to High'),
                      selected: _sortBy == 'price_asc',
                      onSelected: (selected) {
                        setModalState(() => _sortBy = 'price_asc');
                      },
                    ),
                    ChoiceChip(
                      label: const Text('Price: High to Low'),
                      selected: _sortBy == 'price_desc',
                      onSelected: (selected) {
                        setModalState(() => _sortBy = 'price_desc');
                      },
                    ),
                  ],
                ),
                const SizedBox(height: 32),
                Row(
                  children: [
                    Expanded(
                      child: OutlinedButton(
                        onPressed: () {
                          setModalState(() {
                            _minPrice = 0;
                            _maxPrice = 100000;
                            _filterGuests = null;
                            _sortBy = 'name';
                          });
                          setState(() {
                            _minPrice = 0;
                            _maxPrice = 100000;
                            _filterGuests = null;
                            _sortBy = 'name';
                          });
                          Navigator.pop(context);
                          _handleSearch();
                        },
                        child: const Text('Clear'),
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: ElevatedButton(
                        onPressed: () {
                          setState(() {
                            _minPrice = _minPrice;
                            _maxPrice = _maxPrice;
                            _filterGuests = _filterGuests;
                            _sortBy = _sortBy;
                          });
                          Navigator.pop(context);
                          _handleSearch();
                        },
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF1E3A8A),
                        ),
                        child: const Text('Apply Filters'),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final villaProvider = Provider.of<VillaProvider>(context);
    var villas = villaProvider.villas;

    villas = villas.where((v) {
      final price = double.tryParse(v.price.toString()) ?? 0;
      return price >= _minPrice && price <= _maxPrice;
    }).toList();

    if (_sortBy == 'price_asc') {
      villas.sort((a, b) {
        final priceA = double.tryParse(a.price.toString()) ?? 0;
        final priceB = double.tryParse(b.price.toString()) ?? 0;
        return priceA.compareTo(priceB);
      });
    } else if (_sortBy == 'price_desc') {
      villas.sort((a, b) {
        final priceA = double.tryParse(a.price.toString()) ?? 0;
        final priceB = double.tryParse(b.price.toString()) ?? 0;
        return priceB.compareTo(priceA);
      });
    } else {
      villas.sort((a, b) => a.name.compareTo(b.name));
    }

    return Scaffold(
      appBar: AppBar(
        title: const Text('Browse Villas'),
        backgroundColor: const Color(0xFF1E3A8A),
        foregroundColor: Colors.white,
      ),
      body: Column(
        children: [
          Container(
            color: const Color(0xFFF8FAFC),
            padding: const EdgeInsets.all(16),
            child: Column(
              children: [
                TextField(
                  controller: _searchController,
                  decoration: InputDecoration(
                    hintText: 'Search by location, city...',
                    prefixIcon: const Icon(Icons.search),
                    suffixIcon: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        if (_searchController.text.isNotEmpty)
                          IconButton(
                            icon: const Icon(Icons.clear),
                            onPressed: () {
                              _searchController.clear();
                              villaProvider.fetchVillas();
                            },
                          ),
                        IconButton(
                          icon: const Icon(Icons.tune),
                          onPressed: _showFilters,
                        ),
                      ],
                    ),
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                      borderSide: BorderSide.none,
                    ),
                    filled: true,
                    fillColor: Colors.white,
                  ),
                  onSubmitted: (_) => _handleSearch(),
                ),
                const SizedBox(height: 12),
                Row(
                  children: [
                    const Icon(Icons.info_outline, size: 16, color: Colors.grey),
                    const SizedBox(width: 8),
                    Text(
                      '${villas.length} villas found',
                      style: const TextStyle(
                        color: Colors.grey,
                        fontSize: 14,
                      ),
                    ),
                    const Spacer(),
                    if (_filterGuests != null || _minPrice > 0 || _maxPrice < 100000)
                      TextButton.icon(
                        icon: const Icon(Icons.clear, size: 16),
                        label: const Text('Clear Filters'),
                        onPressed: () {
                          setState(() {
                            _minPrice = 0;
                            _maxPrice = 100000;
                            _filterGuests = null;
                          });
                          _handleSearch();
                        },
                      ),
                  ],
                ),
              ],
            ),
          ),
          Expanded(
            child: RefreshIndicator(
              onRefresh: () => villaProvider.fetchVillas(),
              child: villaProvider.isLoading
                  ? const Center(child: CircularProgressIndicator())
                  : villas.isEmpty
                      ? Center(
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              const Icon(
                                Icons.search_off,
                                size: 80,
                                color: Colors.grey,
                              ),
                              const SizedBox(height: 16),
                              const Text(
                                'No villas found',
                                style: TextStyle(
                                  fontSize: 18,
                                  fontWeight: FontWeight.w600,
                                ),
                              ),
                              const SizedBox(height: 8),
                              const Text(
                                'Try adjusting your search or filters',
                                style: TextStyle(color: Colors.grey),
                              ),
                              const SizedBox(height: 24),
                              ElevatedButton(
                                onPressed: () {
                                  setState(() {
                                    _searchController.clear();
                                    _minPrice = 0;
                                    _maxPrice = 100000;
                                    _filterGuests = null;
                                  });
                                  villaProvider.fetchVillas();
                                },
                                child: const Text('Clear All Filters'),
                              ),
                            ],
                          ),
                        )
                      : ListView.builder(
                          padding: const EdgeInsets.all(16),
                          itemCount: villas.length,
                          itemBuilder: (context, index) {
                            return VillaCard(villa: villas[index]);
                          },
                        ),
            ),
          ),
        ],
      ),
    );
  }
}
