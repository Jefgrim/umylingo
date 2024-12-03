<div class="admin-achievement-create">
    <div class="admin-container">
        <h2>Create Achievement for Users</h2>
        
        <form action="/admin/achievements" method="POST" class="achievement-form">
            <!-- CSRF Token (if using Laravel) -->
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            
            <!-- Achievement Title -->
            <div class="form-group">
                <label for="title">Achievement Title</label>
                <input type="text" id="title" name="title" class="form-input" required placeholder="Enter achievement title">
            </div>
            
            <!-- Achievement Description -->
            <div class="form-group">
                <label for="description">Achievement Description</label>
                <textarea id="description" name="description" class="form-input" required placeholder="Describe the achievement"></textarea>
            </div>
            
            <!-- Requirements (For Not Achieved Achievements) -->
            <div class="form-group">
                <label for="requirements">Requirements</label>
                <textarea id="requirements" name="requirements" class="form-input" required placeholder="Enter any requirements for achieving this goal"></textarea>
            </div>
            
            <!-- Achievement Points (Optional) -->
            <div class="form-group">
                <label for="points">Achievement Points</label>
                <input type="number" id="points" name="points" class="form-input" placeholder="Enter points awarded for achieving this achievement">
            </div>
            
            <!-- Create Achievement Button -->
            <button type="submit" class="form-button">Create Achievement</button>
        </form>
    </div>
</div>
