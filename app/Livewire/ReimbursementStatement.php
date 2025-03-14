<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class ReimbursementStatement extends Component
{

    use WithFileUploads;
    public $employeeName;

    public $searchTerm='';
    public $searchEmployee;
    public $selectedPeopleImages = [];  
        public $empId;
    public $employeeId;
    public $isNames = false;
    public $record;

    public $hrempid;
    public $description;
    public $showDetails = true;

    public $activeTab = 'active';
    public $image;
    public $selectedPerson = null;
  
    public $cc_to;
    public $peoples;
    public $peopleData =[];
    public $filteredPeoples;
    public $selectedPeopleNames = [];
    public $selectedPeople = [];
    public $records;
    public $peopleFound = true;
    public $file_path;

    public $gender;
    public $name;
    public $employeess;
    public $showSuccessMessage = false;
   
    public $wishMeOn;
    public $selectedOption = 'all'; 
    public $employeeIds = [];
    public $first_name;
    public $last_name;

    public $Email;
    public $recentHires = [];
    public $employees;

  
    public $employeeDetails = [];
    public $editingField = false;


    public $selectedEmployeeId='';

    public $employeeBox=1;

    public $selectedEmployeeFirstName;
    public $selectedEmployeeImage;

    public $year;

    public $month;
    public $selectedEmployeeLastName;


    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }

   
    

    
    
    
    public function updatesearchTerm()
    {
        $this->searchTerm= $this->searchTerm;
        $this->searchforEmployee();
       
    }


    public $selectedPeopleData=[];
    public function filter()
    {

        $employeeId = auth()->user()->emp_id;

        $companyId = Auth::user()->company_id;


        $this->peopleData = EmployeeDetails::where('first_name', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('emp_id', 'like', '%' . $this->searchTerm . '%')
            ->get();

        $this->filteredPeoples = $this->searchTerm ? $this->employees : null;


    }

    public function selectPerson($emp_id)
    {
     
        try {
         
            // Ensure $this->selectedPeople is initialized as an array
            if (!is_array($this->selectedPeople)) {
                $this->selectedPeople = [];
            }
    
         
            // Find the selected person from the list of employees
            $selectedPerson = $this->employees->where('emp_id', $emp_id)->first();
            if (!empty($this->selectedPeople) && !in_array($emp_id, $this->selectedPeople)) {
                // Flash an error message to the session
                FlashMessageHelper::flashWarning('You can only select one employee ');
                return; // Stop further execution
            }
    
            if ($selectedPerson) {
                // Check if person is already selected
                if (in_array($emp_id, $this->selectedPeople)) {
                    // Person is already selected, so remove them
    
                    // Remove from selectedPeople array
                    $this->selectedPeople = array_diff($this->selectedPeople, [$emp_id]);
    
                    // Remove the person's entry from the selectedPeopleData array
                    $this->selectedPeopleData = array_filter(
                        $this->selectedPeopleData,
                        fn($data) => $data['emp_id'] !== $emp_id
                    );
                } else {
                    // Person is not selected, so add them
                    $this->selectedPeople[] = $emp_id;
    
                    // Create the person's name string
                    $personName = $selectedPerson->first_name . ' ' . $selectedPerson->last_name . ' #(' . $selectedPerson->emp_id . ')';
    
                    // Determine the image URL
                    if ($selectedPerson->image && $selectedPerson->image !== 'null') {
                        $imageUrl = 'data:image/jpeg;base64,' . base64_encode($selectedPerson->image);
                    } else {
                        // Add default image based on gender
                        if ($selectedPerson->gender == "Male") {
                            $imageUrl = asset('images/male-default.png');
                        } elseif ($selectedPerson->gender == "Female") {
                            $imageUrl = asset('images/female-default.jpg');
                        } else {
                            $imageUrl = asset('images/user.jpg');
                        }
                    }
    
                    // Add the person's data to the combined array
                    $this->selectedPeopleData[] = [
                        'name' => $personName,
                        'image' => $imageUrl,
                        'emp_id' => $emp_id
                    ];
                }
    
                // Update the cc_to field with the unique names
                $this->cc_to = implode(', ', array_unique(array_column($this->selectedPeopleData, 'name')));
            }
        } catch (\Exception $e) {
            // Handle the exception
            // Optionally, you can log the error or display a user-friendly message
            $this->dispatch('error', ['message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    
    
    public function closeMessage()
    {
        $this->showSuccessMessage = false;
    }


    

    public function NamesSearch()
    {
        $this->isNames = true;
        $this->selectedPeopleNames = [];
        $this->cc_to = '';
    }

    public function closePeoples()
    {
        $this->isNames = false;
    }

 
    
    
   
    public function updateSelected($option)
    {
        $this->selectedOption = $option; 
        
        // Check if the user is logged in with the 'hr' guard
        if (!auth()->guard('hr')->check()) {
            return;
        }
    
        // Get the logged-in employee ID
        $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
    
        // Fetch the first company_id associated with the logged-in employee
        $companyId = EmployeeDetails::where('emp_id', $loggedInEmpID)
            ->pluck('company_id') 
            ->first();
    
        // Handle cases where the company ID is an array or not
        if (is_array($companyId)) {
            $firstCompanyID = $companyId[0]; 
        } else {
            $firstCompanyID = $companyId; 
        }
    
        // Initialize the query for employees based on company_id
        $query = EmployeeDetails::whereJsonContains('company_id', $firstCompanyID);
    
        // Apply the filters based on the selected option
        switch ($this->selectedOption) {
            case 'current':
                $query->where('employee_status', 'active'); // Filter for current employees
                break;
    
            case 'past':
                $query->whereIn('employee_status', ['rejected', 'terminated']); // Filter for past employees
                break;
    
            case 'intern':
                $query->where('job_role', 'intern'); // Filter for interns
                break;
    
           
            default:
                // No additional filtering, fetch all employees
                case 'all':
                    $query=EmployeeDetails::whereJsonContains('company_id', $firstCompanyID);
                break;
        }
    
        // Fetch the employee IDs after filtering
        $this->employeeIds = $query->pluck('emp_id')->toArray(); // Fetch the filtered employee IDs
        $this->employees = $query->get(); // Fetch the employee data for rendering in the view
  
    
    

    
   }


    
   
    public function mount()
    {
        // Retrieve the logged-in user's emp_id from the 'hr' guard
        if (auth()->guard('hr')->check()) {
            $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
            // Debugging to ensure correct emp_id is fetched
       // Check if emp_id is correct
        } else {
            return;
        }
        $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
        $companyId = EmployeeDetails::where('emp_id', $loggedInEmpID)
->pluck('company_id') // This returns the array of company IDs
->first();
if (is_array($companyId)) {
    $firstCompanyID = $companyId[0]; // Get the first element from the array
} else {
    $firstCompanyID = $companyId; // Handle case where it's not an array
}


        // Fetch the company_id associated with the employee
        $companyID = EmployeeDetails::where('emp_id', $firstCompanyID)
            ->pluck('company_id')
            ->first(); // This will return the first company ID for the employee

        // Outputs the company_id based on whether it's a parent or not
        
    
        // Retrieve the company_id associated with the logged-in emp_id
        $employeeDetails = EmployeeDetails::where('emp_id', $loggedInEmpID)->first();
    
        if (!$employeeDetails) {
            // Debug if no employee details are found for this emp_id
      
            return;
        }
    
        $companyID = $employeeDetails->company_id;
 
        if (!$companyID) {
            // Handle the case where companyID is null
        
            $this->employeeIds = [];
            return;
        }
    
        // Fetch all emp_id values where company_id matches the logged-in user's company_id
        $this->employeeIds = EmployeeDetails::whereJsonContains('company_id', $firstCompanyID)->pluck('emp_id')->toArray();
     

   
   
        // Fetch the employee IDs after filtering
       
        
        if (empty($this->employeeIds)) {
            // Handle the case where no employees are found
         
            return;
        }
    
        // Initialize employees based on search term and company_id
        $employeesQuery = EmployeeDetails::whereJsonContains('company_id', $firstCompanyID)
            ->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('emp_id', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy('first_name')
            ->orderBy('last_name');
    
        // Fetch the employees
        $employees = $employeesQuery->get();
  
        if ($employees->isEmpty()) {
            // Handle the case where no employees match the search term
         
        }
        
    
        // Debug output for fetched employees
     
    
        // Set the component's employee data
        $this->employees = $employees;
      

      
        $this->employeess = EmployeeDetails::whereJsonContains('company_id', $firstCompanyID)
        ->orderBy('hire_date', 'desc') // Order by hire_date descending
      
        ->take(5) // Limit to 5 records
        ->get();
        // Initialize other properties
        $this->peopleData = $this->filteredPeoples ? $this->filteredPeoples : $this->employees;
        $this->selectedPeople = [];
        $this->selectedPeopleNames = [];
    }

    
   
    public function searchforEmployee() {
        if (!empty($this->searchTerm)) {
            // Fetch employees matching the search term
            $this->employees = EmployeeDetails::where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('emp_id', 'like', '%' . $this->searchTerm . '%');
            })->get();
    
            // Include previously selected employees not currently displayed in the search
            foreach ($this->selectedPeople as $selectedEmpId) {
                // Check if selected employee is in the current employees
                if (!$this->employees->contains('emp_id', $selectedEmpId)) {
                    $selectedEmployee = EmployeeDetails::where('emp_id', $selectedEmpId)->first();
                    if ($selectedEmployee) {
                        // Ensure it's marked as checked
                        $selectedEmployee->isChecked = true;
                        $this->employees->push($selectedEmployee);
                    }
                }
            }
    
            // Set isChecked for employees in the current search results
            foreach ($this->employees as $employee) {
                $employee->isChecked = in_array($employee->emp_id, $this->selectedPeople);
            }
        } else {
            $this->employees = collect(); // Reset employees if no search term
        }
    }

    public function updateselectedEmployee($empId)
    {
        // If more than one employee is selected, only allow the first employee to be selected
        if (count($this->selectedPeople) > 1) {
            $this->selectedPeople = array_slice($this->selectedPeople, 0, 1); // Keep only the first selected employee
        } else {
            // If employee is not already selected, proceed with selecting
            if (!in_array($empId, $this->selectedPeople)) {
                $this->selectedPeople[] = $empId; // Add employee to the selected list
            } else {
                // If employee is already selected, remove from the list
                $this->selectedPeople = array_filter($this->selectedPeople, fn($id) => $id != $empId);
            }
        }
    
        // Update the selected employee details
        $this->selectedEmployeeId = $empId;
        $this->selectedEmployeeFirstName = EmployeeDetails::where('emp_id', $empId)->value('first_name');
        $this->selectedEmployeeLastName = EmployeeDetails::where('emp_id', $empId)->value('last_name');
        $this->selectedEmployeeImage = EmployeeDetails::where('emp_id', $empId)->value('image');
        $this->searchTerm='';
        
    }

    public function selectEmployee($empId)
    {
        
        $this->selectedEmployeeId = $empId;
        $this->selectedEmployeeFirstName = EmployeeDetails::where('emp_id', $empId)->value('first_name');
        $this->selectedEmployeeLastName = EmployeeDetails::where('emp_id', $empId)->value('last_name');
        $this->selectedEmployeeImage = EmployeeDetails::where('emp_id', $empId)->value('image');
        $this->searchTerm='';
    }

public $selectedEmployee = null;

public function removeSelectedEmployee()
{
    $this->selectedEmployeeId = null;
    $this->selectedEmployeeFirstName = null;
    $this->selectedEmployeeLastName = null;
}
    public function closeEmployeeBox()
    {
        $this->searchEmployee;
       
       
    }
    public function clearSelectedEmployee()
    {
        $this->selectedEmployeeId='';
        $this->selectedEmployeeFirstName='';
        $this->selectedEmployeeLastName='';
        $this->searchTerm='';
    }

    
    
    public function render()
    {
        $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
        $employeeId = auth()->user()->emp_id;
        if (auth()->guard('hr')->check()) {
            $loggedInEmpID = auth()->guard('hr')->user()->emp_id;
            // Debugging to ensure correct emp_id is fetched
       // Check if emp_id is correct
        } else {
            return;
        }
       
   
        
    
        $loggedInEmpID = auth()->guard('hr')->user()->emp_id;

        // Fetch the company_id associated with the employee
        $companyID = EmployeeDetails::where('emp_id', $loggedInEmpID)
            ->pluck('company_id')
            ->first(); // This will return the first company ID for the employee
        
 
        $companyId = EmployeeDetails::where('emp_id', $loggedInEmpID)
        ->pluck('company_id') // This returns the array of company IDs
        ->first();
        if (is_array($companyId)) {
            $firstCompanyID = $companyId[0]; // Get the first element from the array
        } else {
            $firstCompanyID = $companyId; // Handle case where it's not an array
        }
                    


        // Determine if there are people found
        $peopleFound = $this->employees->count() > 0;
    
        return view('livewire.reimbursement-statement', [
            'employees' => $this->employees,
            'selectedPeople' => $this->selectedPeople,
            'peopleFound' => $peopleFound,
            'searchTerm' => $this->searchTerm,
            // Add any other necessary data for your view here
        ]);
    }

}
 
