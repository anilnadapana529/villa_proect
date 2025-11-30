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
    super.dispose();
  }

  void _handleSearch() {
    final villaProvider = Provider.of<VillaProvider>(context, listen: false);
    villaProvider.searchVillas(location: _searchController.text.trim());
  }

  @override
  Widget build(BuildContext context) {
    final villaProvider = Provider.of<VillaProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Browse Villas'),
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: TextField(
              controller: _searchController,
              decoration: InputDecoration(
                hintText: 'Search by location...',
                prefixIcon: const Icon(Icons.search),
                suffixIcon: IconButton(
                  icon: const Icon(Icons.clear),
                  onPressed: () {
                    _searchController.clear();
                    villaProvider.fetchVillas();
                  },
                ),
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
              ),
              onSubmitted: (_) => _handleSearch(),
            ),
          ),
          Expanded(
            child: RefreshIndicator(
              onRefresh: () => villaProvider.fetchVillas(),
              child: villaProvider.isLoading
                  ? const Center(child: CircularProgressIndicator())
                  : villaProvider.villas.isEmpty
                      ? const Center(child: Text('No villas found'))
                      : ListView.builder(
                          padding: const EdgeInsets.all(16),
                          itemCount: villaProvider.villas.length,
                          itemBuilder: (context, index) {
                            return VillaCard(villa: villaProvider.villas[index]);
                          },
                        ),
            ),
          ),
        ],
      ),
    );
  }
}
