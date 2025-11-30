<?php
include __DIR__ . "/helpers/api.php";

if (!API::isLoggedIn() || API::getUserRole() !== 'owner') {
    header("Location: login.php");
    exit;
}

$token = API::getToken();
$user = API::getUser();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Villa - TopMost</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body style="font-family: 'Poppins', sans-serif; font-size: 14px; background: #ffffff; color: #1e3a8a;">

<style>
.add-villa-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 10px;
}

.page-subtitle {
    color: #718096;
    margin-bottom: 40px;
}

.form-card {
    background: white;
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.form-section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 3px solid #f7fafc;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 25px;
    margin-bottom: 25px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.form-group label .required {
    color: #dc3545;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
}

textarea.form-control {
    min-height: 120px;
    resize: vertical;
}

.amenities-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
}

.amenity-checkbox {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px;
    background: #f7fafc;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s;
}

.amenity-checkbox:hover {
    background: #edf2f7;
}

.amenity-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.amenity-checkbox label {
    margin: 0;
    cursor: pointer;
    font-weight: 500;
}

.image-upload-area {
    border: 3px dashed #cbd5e0;
    border-radius: 12px;
    padding: 40px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
}

.image-upload-area:hover {
    border-color: #667eea;
    background: #f7fafc;
}

.upload-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 15px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.image-preview {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-top: 20px;
}

.preview-item {
    position: relative;
    aspect-ratio: 16/9;
    border-radius: 8px;
    overflow: hidden;
}

.preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.remove-image {
    position: absolute;
    top: 8px;
    right: 8px;
    background: #dc3545;
    color: white;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.2rem;
    line-height: 1;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 15px 40px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1.1rem;
    cursor: pointer;
    transition: transform 0.3s;
}

.btn-primary:hover {
    transform: translateY(-2px);
}

.btn-secondary {
    background: #e2e8f0;
    color: #2d3748;
    border: none;
    padding: 15px 40px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1.1rem;
    cursor: pointer;
    margin-right: 15px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 40px;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .amenities-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .image-preview {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<div class="add-villa-container">
    <div style="margin-bottom: 20px;">
        <a href="owner-dashboard.php" style="color: #667eea; text-decoration: none; font-weight: 600;">
            ← Back to Dashboard
        </a>
    </div>

    <h1 class="page-title">Add New Villa</h1>
    <p class="page-subtitle">Fill in the details below to list your property</p>

    <form id="addVillaForm" enctype="multipart/form-data">
        <div class="form-card">
            <h3 class="form-section-title">Basic Information</h3>

            <div class="form-grid">
                <div class="form-group">
                    <label>Villa Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="e.g., Sunset Beach Villa" required>
                </div>

                <div class="form-group">
                    <label>Location/City <span class="required">*</span></label>
                    <input type="text" name="location" class="form-control" placeholder="e.g., Goa" required>
                </div>
            </div>

            <div class="form-group">
                <label>Full Address <span class="required">*</span></label>
                <textarea name="address" class="form-control" placeholder="Enter complete address with landmarks" required></textarea>
            </div>

            <div class="form-group">
                <label>Description <span class="required">*</span></label>
                <textarea name="description" class="form-control" placeholder="Describe your villa, its unique features, nearby attractions..." required></textarea>
            </div>
        </div>

        <div class="form-card">
            <h3 class="form-section-title">Property Details</h3>

            <div class="form-grid">
                <div class="form-group">
                    <label>Number of Guests <span class="required">*</span></label>
                    <input type="number" name="guests" class="form-control" min="1" placeholder="e.g., 6" required>
                </div>

                <div class="form-group">
                    <label>Number of Bedrooms <span class="required">*</span></label>
                    <input type="number" name="bedrooms" class="form-control" min="1" placeholder="e.g., 3" required>
                </div>

                <div class="form-group">
                    <label>Number of Beds <span class="required">*</span></label>
                    <input type="number" name="beds" class="form-control" min="1" placeholder="e.g., 4" required>
                </div>

                <div class="form-group">
                    <label>Number of Bathrooms <span class="required">*</span></label>
                    <input type="number" name="bathrooms" class="form-control" min="1" placeholder="e.g., 2" required>
                </div>
            </div>
        </div>

        <div class="form-card">
            <h3 class="form-section-title">Pricing</h3>

            <div class="form-grid">
                <div class="form-group">
                    <label>Weekday Price (per night) <span class="required">*</span></label>
                    <input type="number" name="weekday_price" class="form-control" min="0" step="100" placeholder="e.g., 5000" required>
                </div>

                <div class="form-group">
                    <label>Weekend Price (per night) <span class="required">*</span></label>
                    <input type="number" name="weekend_price" class="form-control" min="0" step="100" placeholder="e.g., 7000" required>
                </div>
            </div>
        </div>

        <div class="form-card">
            <h3 class="form-section-title">Amenities</h3>

            <div class="amenities-grid">
                <div class="amenity-checkbox">
                    <input type="checkbox" name="amenities[]" value="Pool" id="amenity-pool">
                    <label for="amenity-pool">Swimming Pool</label>
                </div>
                <div class="amenity-checkbox">
                    <input type="checkbox" name="amenities[]" value="AC" id="amenity-ac">
                    <label for="amenity-ac">Air Conditioning</label>
                </div>
                <div class="amenity-checkbox">
                    <input type="checkbox" name="amenities[]" value="WiFi" id="amenity-wifi">
                    <label for="amenity-wifi">WiFi</label>
                </div>
                <div class="amenity-checkbox">
                    <input type="checkbox" name="amenities[]" value="Parking" id="amenity-parking">
                    <label for="amenity-parking">Parking</label>
                </div>
                <div class="amenity-checkbox">
                    <input type="checkbox" name="amenities[]" value="Kitchen" id="amenity-kitchen">
                    <label for="amenity-kitchen">Kitchen</label>
                </div>
                <div class="amenity-checkbox">
                    <input type="checkbox" name="amenities[]" value="Caretaker" id="amenity-caretaker">
                    <label for="amenity-caretaker">Caretaker</label>
                </div>
                <div class="amenity-checkbox">
                    <input type="checkbox" name="amenities[]" value="Pet Friendly" id="amenity-pet">
                    <label for="amenity-pet">Pet Friendly</label>
                </div>
                <div class="amenity-checkbox">
                    <input type="checkbox" name="amenities[]" value="Party Allowed" id="amenity-party">
                    <label for="amenity-party">Party Allowed</label>
                </div>
            </div>
        </div>

        <div class="form-card">
            <h3 class="form-section-title">Photos <span class="required">*</span></h3>

            <div class="image-upload-area" onclick="document.getElementById('villa-images').click()">
                <div class="upload-icon">
                    <svg width="30" height="30" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                        <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                    </svg>
                </div>
                <h4 style="margin: 0 0 10px 0; color: #2d3748;">Click to upload images</h4>
                <p style="margin: 0; color: #718096;">Upload at least 3 high-quality photos of your villa</p>
            </div>
            <input type="file" id="villa-images" name="images[]" multiple accept="image/*" style="display: none;" onchange="previewImages(event)">

            <div id="image-preview" class="image-preview"></div>
        </div>

        <div class="form-actions">
            <button type="button" class="btn-secondary" onclick="window.location.href='owner-dashboard.php'">Cancel</button>
            <button type="submit" class="btn-primary">Submit for Approval</button>
        </div>
    </form>
</div>

<script>
let selectedFiles = [];

function previewImages(event) {
    const files = Array.from(event.target.files);
    selectedFiles = selectedFiles.concat(files);

    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'preview-item';
            div.innerHTML = `
                <img src="${e.target.result}" alt="Preview">
                <button type="button" class="remove-image" onclick="removeImage(${index})">&times;</button>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function removeImage(index) {
    selectedFiles.splice(index, 1);
    const dt = new DataTransfer();
    selectedFiles.forEach(file => dt.items.add(file));
    document.getElementById('villa-images').files = dt.files;
    previewImages({ target: { files: dt.files } });
}

document.getElementById('addVillaForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    if (selectedFiles.length < 3) {
        alert('Please upload at least 3 images');
        return;
    }

    const formData = new FormData(this);

    selectedFiles.forEach(file => {
        formData.append('images[]', file);
    });

    const amenities = Array.from(document.querySelectorAll('input[name="amenities[]"]:checked'))
        .map(cb => cb.value);
    formData.set('amenities', amenities.join(','));

    try {
        const response = await fetch('/api/owner-add-villa', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer <?= $token ?>'
            },
            body: formData
        });

        const data = await response.json();

        if (data.status) {
            alert('Villa submitted successfully! It will be reviewed by admin.');
            window.location.href = 'owner-dashboard.php';
        } else {
            alert('Error: ' + (data.message || 'Failed to add villa'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while submitting the villa');
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
