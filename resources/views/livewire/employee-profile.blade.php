<div>
<div class="main__body" >
<ul class="nav nav-tabs custom-nav-tabs" role="tablist" style="margin-top:67px">
    <li class="nav-item" role="presentation">
        <a class="nav-link active custom-nav-link" id="simple-tab-0" data-bs-toggle="tab" href="#simple-tabpanel-0" role="tab" aria-controls="simple-tabpanel-0" aria-selected="true">Main</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link custom-nav-link" id="simple-tab-1" data-bs-toggle="tab" href="#simple-tabpanel-1" role="tab" aria-controls="simple-tabpanel-1" aria-selected="false">Activity</a>
    </li>
</ul>

<div class="tab-content pt-5" id="tab-content">
  <div class="tab-pane active" id="simple-tabpanel-0" role="tabpanel" aria-labelledby="simple-tab-0" style="overflow-x: hidden;">
    <div class="row justify-content-center"  >
                        <div class="col-md-9 custom-container d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
    <p class="main-text mb-0" style="width:88%">
        This page allows you to add/edit the profile details of an employee. The page helps you to keep the employee information up to date.
    </p>
    <p class="hide-text" style="cursor: pointer;" wire:click="toggleDetails">
        {{ $showDetails ? 'Hide Details' : 'Info' }}
    </p>
</div>

                            @if ($showDetails)
                                
                           
                            <div class="secondary-text">
    Explore HR Xpert by 
    <span class="hide-text">Help-Doc</span>, watching How-to 
    <span class="hide-text">Videos</span> and 
    <span class="hide-text">FAQ</span>
</div>
@endif

                        </div>
                    </div>
                 

                <div class="row justify-content-center mt-2 "  >
                <div class="col-md-9 custom-container d-flex flex-column bg-white" >
    <div class="row justify-content-center mt-3 flex-column m-0 employee-details-main" >
        <div class="col-md-9">
            <div class="row " style="display:flex;">
                <div class="col-md-11 m-0">
                    <p class="emp-heading" >Start searching to see specific employee details here</p>
                    <div class="col mt-3" style="display: flex;">
             
                        <p class="main-text mt-1">Employee Type:</p>
                       
                        <div class="dropdown">
                        <button class="btn btn dropdown-toggle dp-info" type="button" data-bs-toggle="dropdown" style="font-size:12px">
    Employee: {{ ucfirst($selectedOption) }} 
    <span class="arrow-for-employee"></span><span class="caret"></span>
</button>

    <span class="caret"></span></button>
    <ul class="dropdown-menu" style="font-size:12px; ">
    <li class="updated-drodown" >
        <a href="#" wire:click.prevent="updateSelected('all')" class="dropdown-item custom-info-item">All Employees</a>
    </li>
    <li class="updated-drodown" >
        <a href="#" wire:click.prevent="updateSelected('current')" class="dropdown-item custom-info-item">Current Employees</a>
    </li>
    <li class="updated-drodown" >
        <a href="#" wire:click.prevent="updateSelected('past')" class="dropdown-item custom-info-item">Resigned Employees</a>
    </li>
    <li class="updated-drodown" >
        <a href="#" wire:click.prevent="updateSelected('intern')" class="dropdown-item custom-info-item">Intern</a>
    </li>
</ul>

  </div>
         

                      
                    </div>
                 
                    <div class="profile" >
    <div class="col m-0">
     
    <div class="row d-flex align-items-center">
    <p class="main-text "  style="cursor:pointer" wire:click="NamesSearch">
        Search Employee:
    </p>
    @foreach($selectedPeopleData as $personData)
    <span class="selected-person d-flex align-items-center">
        <img class="profile-image-selected" src="data:image/jpeg;base64,{{ $personData['image'] ?? '-' }}">

        <p class="selected-name mb-0">
            @php
                // Split the name into parts
                $nameParts = explode(' ', $personData['name']);

                // Capitalize the first letter of the first name
                $firstName = isset($nameParts[0]) ? ucfirst(strtolower($nameParts[0])) : '';

                // Get the last name parts (excluding emp_id)
                $lastNameParts = array_slice($nameParts, 1); // Get all parts after the first name

                // Capitalize each part of the last name
                $formattedLastName = implode(' ', array_map('ucfirst', array_map('strtolower', $lastNameParts)));

                // Combine first and last names
                $fullName = trim($firstName . ' ' . $formattedLastName);
            @endphp
            {{ $fullName }} <!-- Display only the full name -->
        </p>

        <p class="emp-id mb-0" style="font-size: 12px; color: white;">
            (#{{ strtoupper(e((string) $personData['emp_id'])) }}) <!-- Display emp_id separately -->
        </p>

        <svg class="close-icon-person"  
             wire:click="removePerson('{{ e((string) $personData['emp_id']) }}')" 
             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
            <path d="M6 18L18 6M6 6l12 12" stroke="#3b4452" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </span>
@endforeach











</div>





               
       
    
        <div class="col-md-6 col-12"> 

@if($isNames)
<div class="col-md-6 search-bar" >
<div class="input-group4" >

<input 
wire:model.debounce.500ms="searchTerm" placeholder="Search employees..."

type="text" 
class="form-control search-term" 
placeholder="Search for Emp.Name or ID" 
aria-label="Search" 
aria-describedby="basic-addon1"
/>

<div class="input-group-append" style="display: flex; align-items: center;">

<button 
  
 wire:click="searchforEmployee"  wire:model.debounce.500ms="searchTerm"  style="<?php echo ($searchEmployee) ? 'display: block;' : ''; ?>" 
    class="search-btn" 
    type="button" >
    <i class='bx bx-search' style="color: white;"></i>
</button>

<button 
    wire:click="closePeoples"   
    type="button" 
    class="close-btn rounded px-1 py-0" 
    aria-label="Close" 
   
>
    <span aria-hidden="true" style="color: white; font-size: 24px; line-height: 0;">×</span>
</button>

</div>
</div>
<div>


<!-- Display the Search Results -->
@if ($peopleData && $peopleData->isEmpty())
                                    <div class="search-container">
                                        No People Found
                                    </div>
                                    @else
                                    @if(count($employees) > 0)
                                    @if (session()->has('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 5px 10px; width: 100%; max-width: 500px; margin: 10px auto;">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="margin-left: 2px; font-size: 8px;">
            &times;
        </button>
    </div>
@endif



                                    @foreach($employees as $employee)

    @if(stripos($employee->first_name . ' ' . $employee->last_name, $searchTerm) !== false)
        <label wire:click="selectPerson('{{ $employee->emp_id }}')" class="search-container">
            <div class="row align-items-center">
                <div class="col-auto"> 
                    <input type="checkbox" id="employee-{{ $employee->emp_id }}" 
                           wire:click="updateselectedEmployee('{{ $employee->emp_id }}')"  
                           wire:model="selectedPeople" 
                           value="{{ $employee->emp_id }}" class="form-check-input custom-checkbox-information"
                           {{ in_array($employee->emp_id, $selectedPeople) || $employee->isChecked ? 'checked' : '' }}>
                </div>
                <div class="col-auto">
                    @if($employee->image && $employee->image !== 'null')
                    
                        <img class="profile-image"  src="data:image/jpeg;base64,{{($people->image ??'-') }}" >
                    @else
                        @if($employee->gender == "Male")
                            <img class="profile-image" src="{{ asset('images/male-default.png') }}" alt="Default Male Image">
                        @elseif($employee->gender == "Female")
                            <img class="profile-image" src="{{ asset('images/female-default.jpg') }}" alt="Default Female Image">
                        @else
                            <img class="profile-image" src="{{ asset('images/user.jpg') }}" alt="Default Image">
                        @endif
                    @endif
                </div>

                <div class="col">
                    <h6 class="name" class="mb-0" style="font-size: 12px; color: white;">
                        @php
                            // Capitalize the first letter of the first name
                            $formattedFirstName = ucfirst(strtolower($employee->first_name));

                            // Capitalize each part of the last name
                            $lastNameParts = explode(' ', strtolower($employee->last_name));
                            $formattedLastName = implode(' ', array_map('ucfirst', $lastNameParts));
                        @endphp
                        {{ $formattedFirstName }} {{ $formattedLastName }}
                    </h6>
                    <p class="mb-0" style="font-size: 12px; color: white;">(#{{ $employee->emp_id }})</p>
                </div>
            </div>
        </label>
    @endif
@endforeach
@endif

@endif


</div>





</div>
@endif
</div> 
 
    </div>
</div>

                    </div>
             
                <div class="col-md-1">
    <!-- Modified image container to have a fixed height -->
    <div class="image-container d-flex align-items-end" >
        <img src="{{ asset('images/employeeleave.png') }}"  alt="Employee Image" style="height: 180px; width:280px;align-items:end">
    </div>
</div>

            </div>
        </div>
        
  

</div>

 
                
        </div>
    </div>

   
 
 
    @if(!empty($selectedPeople))
    <div class="row mt-3 p-0 justify-content-center">
   
    </div>
    
   
    @foreach($selectedPeople as $emp_id)
                @php
                    $employee = $employees->firstWhere('emp_id', $emp_id);
                @endphp
            



@if($employee)
@if ($showSuccessMessage)
 
 <div class="alert alert-success alert-dismissible fade show" role="alert" 
 style="font-size: 12px; padding: 5px 10px; display: flex; align-items: center; justify-content: space-between; width: 300px; margin: 0 auto;" 
 wire:poll.5s="closeMessage">
 Profile updated successfully!
 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" 
     style="font-size: 8px;align-items:center;margin-top:-7px">
     &times;
 </button>
</div>


@endif

<div class="card-profile mx-auto" >
  
        <div class="profile-header " >
            
            {{-- Employee Image --}}
            @if($employee->image && $employee->image !== 'null')
                <img class="profile-image"     src="data:image/jpeg;base64,{{($employee->image ??'-') }}" >
            @else
                @if($employee->gender == "Male")
                    <img class="profile-image" src="{{ asset('images/male-default.png') }}" alt="Default Male Image">
                @elseif($employee->gender == "Female")
                    <img class="profile-image" src="{{ asset('images/female-default.jpg') }}" alt="Default Female Image">
                @else
                    <img class="profile-image" height="40" width="40" src="{{ asset('images/user.jpg') }}" alt="Default Image">
                @endif
            @endif

            {{-- Employee Name --}}
            <p style="margin-left: 15px; font-size:12px; justify-content:center;">{{ $employee->first_name }} {{ $employee->last_name }}</p>

            {{-- Update Button and File Input --}}
            <div class="d-flex align-items-center gap-2" style="margin-left: auto;">
    <!-- Camera Icon to Open File Input for Camera -->
<!-- Camera Icon to Open File Input -->


<!-- Update Profile Button, Disabled by Default -->


<!-- Separate Button to Update Profile -->
<button type="button" wire:click="updateProfile('{{ $employee->emp_id }}')" class="custom-file-upload">
<i class="bx bx-camera" style="color:white;" onclick="document.getElementById('imageInput').click();"></i>

<!-- Hidden File Input for Selecting Image -->
<input type="file" id="imageInput" wire:model="image" accept="image/*;capture=camera" style="display: none;" />
    Update Profile
</button>


              
            </div>

            {{-- Image Upload Error --}}
            @if ($errors->has('image'))
                <span class="text-danger">{{ $errors->first('image') }}</span><br>
            @endif
        </div>
  
</div>

    <div class="row align-items-center ">
        <div class="card mx-auto" >
        <div class="card-header">
    <p style="color:#3b4452; font-weight: 500;">Employee Information</p>

  <i style="color:#3b4452">
  @if($currentEditingProfileId == $employee->emp_id)
        <i wire:click="cancelProfile()"  class="bx bx-x me-1" style="cursor: pointer; color: black;"></i>
        <i wire:click="saveProfile('{{ $employee->emp_id }}')" class="bx bx-save" style="cursor: pointer; color: black;"></i>
    @else
        <i wire:click="editProfile('{{ $employee->emp_id }}')" class="bx bx-edit ml-auto"  style="cursor: pointer; color: black;"></i>
    @endif
  </i> 
</div>


            <div class="card-row">
                <div class="col-md-3  edit-headings" >Title
                @if($currentEditingProfileId == $employee->emp_id)



                <div><input type="text" class="form-control mt-2 input-width" wire:model="title" placeholder="Title"></div>
                @else
                <div class="editprofile " >{{$employee->empPersonalInfo->title ?? '-'}} </div>
                @endif
                </div>

                <div class="col-md-3  edit-headings" >Nick Name
                @if($currentEditingProfileId == $employee->emp_id)

                <div ><input  type="text" class="form-control mt-2 input-width" wire:model="nickName" placeholder="Nickname"></div>
                @else
                <div class="editprofile " >{{$employee->empPersonalInfo->nick_name ?? '-'}} </div>
                @endif
                </div>
                <div class="col-md-3  edit-headings" >Gender
                @if($currentEditingProfileId == $employee->emp_id)
                <div > <input type="text" class="form-control mt-2 input-width" wire:model="gender" placeholder="Gender"></div>
                @else
                <div class="editprofile" >{{$employee->gender ?? '-'}} </div>
                @endif
                </div>
                <div class="col-md-3 edit-headings" >Name
                @if($currentEditingProfileId == $employee->emp_id)
                <div class=" mb-3"><input  type="text" class="form-control mt-2 input-width" wire:model="name" placeholder="Name"></div>
                @else
                <div class="editprofile">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                @endif
                </div>
            </div>
            @if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 5px 10px; width: 100%; max-width: 500px; margin: 10px auto;">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="margin-left: 2px; font-size: 8px;">
            &times;
        </button>
    </div>
@endif
@if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 5px 10px; width: 100%; max-width: 500px; margin: 10px auto;">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="margin-left: 2px; font-size: 8px;">
            &times;
        </button>
    </div>
@endif

            <div class="card-row" >

            <div class="col-md-3 edit-headings" >Mobile

            @if($currentEditingProfileId == $employee->emp_id)
                <div class="mb-2"><input type="text" class="form-control mt-1 input-width" wire:model="emergency_contact" placeholder="Mobile"></div>
                @else
                <div class="editprofile mb-3" >{{$employee->emergency_contact ?? '-'}} </div>
                @endif
                </div>
                <div class="col-md-3 edit-headings">Email
                @if($currentEditingProfileId == $employee->emp_id)
                <div class="mb-2"><input  type="text" class="form-control mt-1 input-width" wire:model="Email" placeholder="Email"></div>
                @else
                <div class="editprofile mb-3" >{{$employee->email ?? '-'}} </div>
                @endif
                </div>
                <div class="col-md-3 edit-headings">Extension
                @if($currentEditingProfileId == $employee->emp_id)
                <div class="mb-2"><input  type="text" class="form-control mt-1 input-width" wire:model="extension" placeholder="Extension"></div>
                @else
                <div class="editprofile mb-3">{{ $employee->extension }} </div>
                @endif
                </div>

              
            </div>

      
        </div>
        <div class="card mx-auto mt-3" style="width: 70%; height: auto;">
        <div class="card-header d-flex justify-content-between align-items-center" style="font-size: 15px; background:white;">
    <p class="mb-0" style=" font-weight: 500;">Personal Information</p>
    <div style="font-size: 14px;">
   <i> @if($currentEditingPersonalProfileId == $employee->emp_id)
    <i wire:click="cancelpersonalProfile('{{ $emp_id }}')" class="bx bx-x me-1" style="cursor: pointer;"></i>
    <i wire:click="savepersonalProfile('{{ $emp_id }}')" class="bx bx-save" style="cursor: pointer;"></i>
@else
    <i wire:click="editpersonalProfile('{{ $emp_id }}')" class="bx bx-edit ml-auto"style="cursor: pointer;"></i>
@endif
</i>

    </div>
</div>


    <div class="card-row" >
        <div class="col-md-3 edit-headings">DOB
        @if($currentEditingPersonalProfileId == $employee->emp_id)
                <div class="mb-2">   <input type="date" class="form-control mt-1 input-width" wire:model="dob" ></div>
                @else
                <div class="editprofile mb-3" >{{ isset($employee->empPersonalInfo->date_of_birth) ? \Carbon\Carbon::parse($employee->empPersonalInfo->date_of_birth)->format('d/m/Y') : '-' }}</div>
                @endif
                </div>
                <div class="col-md-3 edit-headings" >Blood Group
                @if($currentEditingPersonalProfileId == $employee->emp_id)
                <div class="mb-2">      <input type="text" class="form-control mt-1 input-width" wire:model="BloodGroup" placeholder="Blood Group" ></div>
                @else
                <div class="editprofile mb-3" >{{ $employee->empPersonalInfo->blood_group ?? '-' }} </div>
                @endif
                </div>
                <div class="col-md-3 edit-headings" >Marital Status
                @if($currentEditingPersonalProfileId == $employee->emp_id)
                <div class="mb-2"> <input type="text" class="form-control mt-1 input-width" wire:model="MaritalStatus" placeholder="Marital Status" ></div>
                @else
                <div class="editprofile mb-3">{{ $employee->empPersonalInfo->marital_status ?? '-' }}</div>
                @endif
     
    </div>


</div>

    </div>
    </div>
@endif

    @endforeach
@endif

</div>

  <div class="tab-pane" id="simple-tabpanel-1" role="tabpanel" aria-labelledby="simple-tab-1" style="overflow-x: hidden;">
    <div class="row " style="font-size:12px;margin-left:30px">
      <h6 style="text-decoration:underline">Activity Stream :</h6>
      <div id="employee-container mt-10">
      @foreach($employeess as $employee)
  <span style="font-weight:600">
      Added New Employee: ({{ $employee->emp_id }}) {{ $employee->first_name }} {{ $employee->last_name }}
  </span>
  @if (!$loop->first)<br>@endif
  <p class="main-text">
      Hire Date: 
      @if($employee->hire_date)
          @php
              $hireDate = \Carbon\Carbon::parse($employee->hire_date);
          @endphp
          ({{ $hireDate->format('M d, Y') }})
      @else
          N/A
      @endif
  </p>
  @if (!$loop->first)<br>@endif
@endforeach

</div>



</div>
</div>
</div>
<script>
document.getElementById('imageInput').addEventListener('change', function () {
    const fileName = this.files[0] ? this.files[0].name : 'Update Profile';
    this.nextElementSibling.textContent = fileName;
});
</script>
</div>