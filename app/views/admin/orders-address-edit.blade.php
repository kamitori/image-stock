<!-- FORM -->
    <form name="address-form-{{$address['is_billing']}}" id="address-form-{{$address['is_billing']}}" action="#" method="post">
        <input type="hidden" name="address_id" id="address_id" value="{{$address['id']}}" />
        <input type="hidden" name="user_id" id="user_id" value="{{$address['user_id']}}" />
        <input type="hidden" name="is_billing" id="is_billing" value="{{$address['is_billing']}}" />
      <fieldset>
        <legend>Enter your details</legend>
        <div class="form-group">
          <label for="first_name">First Name</label>
          <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Enter First Name" value="{{ isset($address['first_name']) ? $address['first_name'] : '' }}" required >
        </div>
        <div class="form-group">
          <label for="last_name">Last Name</label>
          <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Enter Last Name" value="{{ isset($address['last_name']) ? $address['last_name'] : '' }}" required >
        </div>
        <div class="form-group">
          <label for="organization">Company</label>
          <input type="text" class="form-control" name="organization" id="organization" placeholder="Enter Company" value="{{ isset($address['organization']) ? $address['organization'] : '' }}">
        </div>
        <div class="form-group">
          <label for="phone">Phone</label>
          <input type="tel" class="form-control" name="phone" id="phone" placeholder="Enter Phone" value="{{ isset($address['phone']) ? $address['phone'] : '' }}" required >
        </div>        
        <div class="form-group">
          <label for="street">Adress 1</label>
          <input type="text" class="form-control" name="street" id="street" placeholder="Enter Adress 1" value="{{ isset($address['street']) ? $address['street'] : '' }}"  required >
        </div>	
        
        <div class="form-group">
          <label for="street_extra">Adress 2</label>
          <input type="text" class="form-control" name="street_extra" id="street_extra" placeholder="Enter Adress 2" value="{{ isset($address['street_extra']) ? $address['street_extra'] : '' }}">
        </div>
        <div class="form-group">
          <label for="city">City</label>
          <input type="text" class="form-control" name="city" id="city" placeholder="Enter City" value="{{ isset($address['city']) ? $address['city'] : '' }}"  required >
        </div>
        
        <div class="form-group">
          <label for="post_code">Post Code</label>
          <input type="text" class="form-control" name="post_code" id="post_code" placeholder="Enter Post Code" value="{{ isset($address['post_code']) ? $address['post_code'] : '' }}" required maxlength="10" >
        </div>
        <div class="form-group">
          <label for="country_a2">Country</label>
          <select class="form-control" name="country_a2" id="country_a2" onchange="getStates(this.value, 'div-load-states-{{$address['id']}}')"  required >
            <option value=""> - Select Country - </option>
            
            <?php $country_a2 = isset($address['country_a2']) ? $address['country_a2'] : ''; ?>
            @foreach($countries as $value)
            @if($country_a2 == $value->a2)
                <option value="{{ $value->a2 }}" selected="selected">{{ $value['name'] }}</option>
            @else
                <option value="{{ $value->a2 }}">{{ $value->name }}</option>
            @endif
            @endforeach
          </select>
        </div>							
        <div class="form-group" id="div-load-states-{{$address['id']}}">
            <label for="state_a2">Region/State</label>
            <select class="form-control" name="state_a2" id="state_a2" >
                <option value=""> - Select Region/State - </option>
                @if($country_a2 != '')
                    <?php $state_a2 = isset($address['state_a2']) ? $address['state_a2'] : ''; ?>
                    @foreach($states as $value)
                    @if($state_a2 == $value->a2)
                        <option value="{{ $value->a2 }}" selected="selected">{{ $value['name'] }}</option>
                    @else
                        <option value="{{ $value->a2 }}">{{ $value->name }}</option>
                    @endif
                    @endforeach
                @endif
            </select>
        </div>
        <button type="button" class="btn btn-primary" onclick="updateAddress('address-form-{{$address['is_billing']}}')">Update</button>
        <button type="button" class="btn btn-info" onclick="cancelEdit('div-address-{{$address['is_billing']}}')">Cancel</button>
      </fieldset>
    </form>
<!-- /FORM -->