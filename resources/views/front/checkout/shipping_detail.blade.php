<div class="row clsAddressField" id="addNewAddressDiv" style="display:block;">
    <input type="hidden" id="selected_address_id" value="" name="selected_address_id" />
    <div class="col-md-12">
        <div class="form-group">
            <label for="full_name">Address Title <span class="asteric">*</span></label>
            <input id="full_name" name="full_name" class="form-control clsLoginField"  type="text" placeholder="e.g. Home,Office,Dwaniya...etc."  tabindex="4">
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="country_id">Country <span class="asteric">*</span></label>
            <input type="hidden" value="" name="country" id="country">
            <select class="form-control clsLoginField" id="country_id" name="country_id" tabindex="5">
                @foreach($country as $item)
                <option value="{{$item->id}}" data-countryname = {{$item->country_name}} {{$item->country_name == 'Kuwait' || $item->country_name == 'KUWAIT' ? 'selected' : ''}} >{{$item->country_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group hide">
            <label for="city_id">Area <span class="asteric">*</span></label>
            <input type="hidden" value="" name="city" id="city">
            {{--<input type="hidden" value="{{!empty($address->city_id) ? $address->city_id : ''}}" name="city_ids" id="city_ids">--}}
            <select class="form-control clsLoginField" id="city_id" name="city_id" tabindex="6">
                <option value="{{!empty($address) && !empty($city) &&  $address->city_id == $city->id ? $address->city_id : ''}}" {{!empty($address) && !empty($city) &&  $address->city_id == $city->id ? 'selected' : ''}}>{{ !empty($city) && $address->city_id == $city->id ?  $city->city_name : 'Select Area'}}</option>
            </select>
        </div>
        <div class="form-group hide" id="additional_directions_div">
            <label for="additional_directions">Additional Directions </label>
            <input id="additional_directions"  name="additional_directions" class="form-control clsLoginField" type="text"  placeholder="Additional Directions" tabindex="7">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="block">Block <span class="asteric">*</span></label>
            <input id="block" name="block" class="form-control clsLoginField" type="text" placeholder="Block" tabindex="8">
        </div>
        <div class="form-group">
            <label for="street">Street <span class="asteric">*</span></label>
            <input id="street" name="street" class="form-control clsLoginField" type="text" placeholder="Street" tabindex="10">
        </div>
        <div class="form-group">
            <label for="avenue">Avenue </label>
            <input type="text" id="avenue" name="avenue" class="form-control clsLoginField" placeholder="Avenue" tabindex="12">
        </div>
        <div class="form-group">
            <label for="building">Building <span class="asteric">*</span></label>
            <input type="text" id="building" name="building" class="form-control clsLoginField" placeholder="Building" tabindex="14">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="floor">Floor</label>
            <input type="text" id="floor" name="floor" class="form-control clsLoginField" placeholder="Floor" tabindex="9">
        </div>
        <div class="form-group">
            <label for="apartment">Apartment</label>
            <input type="text" id="apartment" name="apartment" class="form-control clsLoginField" placeholder="Apartment" tabindex="11">
        </div>

        <div class="form-group">
            <label for="mobile">Mobile <span class="asteric">*</span></label>
            <input type="text" id="mobile_no" name="mobile" class="form-control phonNumberOnly numeric clsLoginField" placeholder="Mobile" tabindex="13" >

        </div>
        <div class="form-group">
            <label for="landline">Landline Number</label>
            <input type="text" id="landline" name="landline" class="form-control phonNumberOnly clsLoginField numeric" placeholder="Landline Number" tabindex="15">
        </div>
    </div>
   


</div>