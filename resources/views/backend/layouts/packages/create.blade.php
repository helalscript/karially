@extends('backend.app')
@section('title', 'Packages')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Create Package</h3>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">

                <form action="{{ route('packages.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        <!-- Title -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">Title<span class="text-danger">*</span></label>
                            <input type="text" name="title"
                                class="form-control h-55 @error('title') is-invalid @enderror" value="{{ old('title') }}"
                                required placeholder="Enter Title">
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Trial Days -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">Trial Period Days</label>
                            <input type="number" name="trial_period_days"
                                class="form-control h-55 @error('trial_period_days') is-invalid @enderror"
                                value="{{ old('trial_period_days') }}" placeholder="Trial period (optional)">
                            @error('trial_period_days')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Billing Cycle -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">Billing Cycle<span class="text-danger">*</span></label>
                            <select name="billing_cycle"
                                class="form-select h-55 @error('billing_cycle') is-invalid @enderror" required>
                                <option value="">Select Billing Cycle</option>
                                <option value="monthly" {{ old('billing_cycle') == 'monthly' ? 'selected' : '' }}>Monthly
                                </option>
                                <option value="yearly" {{ old('billing_cycle') == 'yearly' ? 'selected' : '' }}>Yearly
                                </option>
                                <option value="lifetime" {{ old('billing_cycle') == 'lifetime' ? 'selected' : '' }}>Lifetime
                                </option>
                            </select>
                            @error('billing_cycle')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">Price<span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price"
                                class="form-control h-55 @error('price') is-invalid @enderror" value="{{ old('price') }}"
                                required placeholder="Enter Price">
                            @error('price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Features Select -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">Select Features<span class="text-danger">*</span></label>
                            <select name="feature_id[]" id="feature_id"
                                class="form-select h-55 @error('feature_id') is-invalid @enderror" multiple required>
                                <option value="" disabled>Select Features</option>
                                @foreach ($features as $feature)
                                    <option value="{{ $feature->id }}"
                                        {{ in_array($feature->id, old('feature_id', [])) ? 'selected' : '' }}>
                                        {{ $feature->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('feature_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Feature Input Blocks -->
                    <div id="feature-fields-wrapper" class="row"></div>

                    <!-- Submit Button -->
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary px-4 fs-16">
                                <i class="ri-check-line text-white"></i> Submit
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script>
    $(document).ready(function () {
        const features = @json($features->pluck('title', 'id'));

        // Render feature fields when selected
        $('#feature_id').change(function () {
            const selected = $(this).val() || [];
            const wrapper = $('#feature-fields-wrapper');
            wrapper.empty();

            selected.forEach(function (featureId) {
                const featureName = features[featureId];

                const html = `
                    <div class="col-lg-12 mb-3 border rounded p-3 feature-block" data-feature-id="${featureId}">
                        <h5 class="mb-3">${featureName}</h5>
                        <input type="hidden" name="selected_features[]" value="${featureId}">
                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label class="label text-secondary">Access Level</label>
                                <select class="form-select h-55 access-level-select" name="access_lavel[${featureId}]" data-feature="${featureId}">
                                    <option value="view_only">View Only</option>
                                    <option value="read_only">Read Only</option>
                                    <option value="unlimited">Unlimited</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>

                            <div class="col-lg-6 mt-2">
                                <label class="label text-secondary">Feature Description</label>
                                <textarea class="form-control h-55" name="features_description[${featureId}]" placeholder="Description for this feature in the selected plan" required></textarea>
                            </div>

                            <!-- Custom Fields will be appended here only when needed -->
                            <div class="col-lg-12 mt-3" id="custom-fields-wrapper-${featureId}"></div>
                        </div>
                    </div>
                `;
                wrapper.append(html);
            });
        });

        // Access level change handler (toggle dynamic custom fields)
        $(document).on('change', '.access-level-select', function () {
            const featureId = $(this).data('feature');
            const value = $(this).val();
            const container = $(`#custom-fields-wrapper-${featureId}`);

            if (value === 'custom') {
                const customFieldsHtml = `
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="label text-secondary">Custom Tag</label>
                            <input type="text" class="form-control h-55" name="custom_tag[${featureId}]" placeholder="e.g. goal, resume" required>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label text-secondary">Custom Value</label>
                            <input type="number" class="form-control h-55" name="custom_value[${featureId}]" placeholder="e.g. 1, 3, 10" required>
                        </div>
                    </div>
                `;
                container.html(customFieldsHtml); // append inside the wrapper
            } else {
                container.empty(); // remove when not custom
            }
        });
    });
</script>

@endpush
