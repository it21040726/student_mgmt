<div class="card p-4 shadow-sm mb-4" style="border-radius: 12px;">
    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label fw-semibold">First Name</label>
            <input type="text" name="first_name" class="form-control" required
                value="{{ old('first_name', $student->first_name ?? '') }}">
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold">Last Name</label>
            <input type="text" name="last_name" class="form-control" required
                value="{{ old('last_name', $student->last_name ?? '') }}">
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" class="form-control" required
                value="{{ old('email', $student->email ?? '') }}">
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold">Phone</label>
            <input type="text" name="phone" class="form-control"
                value="{{ old('phone', $student->phone ?? '') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label fw-semibold">Date of Birth</label>
            <input type="date" name="DOB" class="form-control"
                value="{{ old('DOB', $student->DOB ?? '') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label fw-semibold">Gender</label>
            <select name="gender" class="form-select">
                <option value="">Select</option>
                <option value="male" {{ old('gender', $student->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender', $student->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('gender', $student->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <div class="col-md-4">
              <label for="course" class="form-label">Course</label>
              <select class="form-select" name="course" id="course">
                <option value="" selected disabled>Choose...</option>
                <option>Computer Science</option>
                <option>Business</option>
                <option>Engineering</option>
                <option>Arts</option>
              </select>
              <div class="invalid-feedback">Select the Course.</div>
            </div>
        <div class="col-12">
            <label class="form-label fw-semibold">Address</label>
            <textarea name="address" class="form-control" rows="3">{{ old('address', $student->address ?? '') }}</textarea>
        </div>

         <!-- <div class="col-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="terms" >
                <label class="form-check-label" for="terms">
                    Terms and conditions and privacy policy must be accepted.
                </label>
              </div>
            </div> -->

    </div>
</div>
