
    <div class="row" style="padding:20px">
        <div>
            <form action="#" method="post" class="form-signin">

                <div class="form-group">
                    <label class="control-label">First name:</label>
                    <input type="text" name="first_name" id="first_name" value="{{ $user->first_name }}" class="form-control" required>
                    @if(isset($errors) && $errors->has('first_name'))
                        <div class="has-error" style="color:#a94442;">{{ $errors->first('first_name')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="control-label">Last name:</label>
                    <input type="text" name="last_name" id="last_name" value="{{ $user->last_name }}" class="form-control" required>
                    @if(isset($errors) && $errors->has('last_name'))
                        <div class="has-error" style="color:#a94442;">{{ $errors->first('last_name')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="control-label">About:</label>
                    <textarea name="description" id="description" class="form-control" rows="10">{{ $user->description }}</textarea>
                    @if(isset($errors) && $errors->has('description'))
                        <div class="has-error" style="color:#a94442;">{{ $errors->first('description')}}</div>
                    @endif
                </div>

         		 <button id="edit-confirm" class="btn" type="button" onclick="editConfirm()">Change</button>
                 <button id="cancel-edit-confirm" class="btn" type="button" onclick="cancelEditConfirm()">Cancel</button>

            </form>
        </div>
    </div>