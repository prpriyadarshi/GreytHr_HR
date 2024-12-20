<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\HolidayCalendar;
use App\Models\LeaveRequest;
use App\Models\RegularisationDates;
use App\Models\SwipeRecord;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AttendanceInfoForHrDisplay extends Component
{
    public $searchEmployee=0;

    public $calendar;
    public $searchTerm = '';
    public $employees;

    public $currentMonthTotalMinutes;

    public $previousMonthTotalMinutes;

    public $employeeBox=1;

    public $selectedEmployeeFirstName;

    public $year;

    public $totalWorkingDays;

    public $totalLateInSwipes;

    public $totalnumberofEarlyOut;

    public $totalnumberofLeaves;

    public $totalnumberofAbsents;

    public $averageWorkHoursForModalTitle=0;

    public $avergageFirstInTime;

    public $averageLastOutTime;
    public $month;
    public $selectedEmployeeLastName;

   
    public $currentDate2;
    public $hours;

    public $country;

    public $city;

    public $postal_code;
    public $totalWorkingPercentage;
    public $minutesFormatted;

    public $avgWorkHoursFromJuly = 0;
    public $last_out_time;

    public $percentageDifference;
    public $currentDate;
    public $date1;

    public $avgSignInTime;


    public $view_student_first_name;

    public $view_student_last_name;
    public $averageWorkHours;

    public $percentageOfWorkHrs;
    public $percentageOfWorkHours;
    public $CurrentDate;
    public $avgSignOutTime;

    public $swipe_records_count;
    public $clickedDate;
    public $currentWeekday;


    public $totalDays;
    
    public $selectedDate;
    public $shortFallHrs;
    public $work_hrs_in_shift_time;
    public $swipe_record;
    public $holiday;
    public $leaveApplies;
    public $first_in_time;
       public $currentDate2record;
    
    public $actualHours = [];
    public $firstSwipeTime;
    public $secondSwipeTime;
    public $swiperecords;
    public $currentDate1;

    public $showCalendar = true;
    public $date2;
    public $modalTitle = '';

    public $countofAbsent;
    public $view_student_swipe_time;
    public $view_student_in_or_out;
    public $swipeRecordId;

    public $swiperecordsfortoggleButton;
    public $swiperecord;
    public $from_date;
    public $to_date;
    public $status;
    public $dynamicDate;
    public $view_student_emp_id;
    public $view_employee_swipe_time;
    public $currentDate2recordexists;


    public $defaultfaCalendar = 1;
    public $dateclicked;
    public $view_table_in;

    public $count;
    public $view_table_out;
    public $employeeDetails;
    public $changeDate = 0;
    public $student;
    public $selectedRecordId = null;

    
    public $regularised_by;

    public $regularised_date;

    public $regularised_reason;

    public $regularised_date_to_check;

    public $avgWorkingHrsForModalTitle;
    public $legend = true;
    public $isNextMonth = 0;
    public $record;

    public $dateToCheck;

    public $Swiperecords;
    public $employeeId;



    public $employeeIdForRegularisation;

   
    public $totalDurationFormatted;

    public $avgDurationFormatted;
    public $öpenattendanceperiod = false;

    public $averageFormattedTime = '00:00';
    public $totalDurationFormatted1;
    public $errorMessage;
    public $showRegularisationDialog = false;
    public $distinctDates;
    public $isPresent;
    public $table;
    public $previousMonth;
    public $session1 = 0;
    public $session2 = 0;
    public $session1ArrowDirection = 'right';
    public $session2ArrowDirection = 'right';

    public $averageHoursWorked;

    public $totalcount = 0;

    public $employeeType = 'all';
    public $averageMinutesWorked;
    public $avgSwipeInTime = null;
    public $avgSwipeOutTime = null;
    public $totalmodalDays;

    public $avgWorkHoursPreviousMonth;
    public $averageworkhours;
    public $averageWorkHrsForCurrentMonth = null;
    public $averageFormattedTimeForCurrentMonth;
    public $holidayCountForInsightsPeriod;
    public $weekendDays = 0;
    public $daysWithRecords = 0;

    public $selectedOption = 'all'; 
    public $percentageinworkhrsforattendance;
    public $leaveTaken = 0;
    public $totalHoursWorked = 0;

    public $start_date_for_insights;
    public $toggleButton = false;
    public $totalMinutesWorked = 0;
    public $avgWorkHours = 0;
    public $avgLateIn = 0;
    public $avgEarlyOut = 0;

    public $k, $k1;
    public $showMessage = false;

    public $employee;
    public $selectedEmployeeId;

    public function mount($selectedEmployeeId)
    {
        $this->selectedEmployeeId = $selectedEmployeeId;
        $this->employee = EmployeeDetails::where('emp_id', $this->selectedEmployeeId)->select('emp_id', 'first_name', 'last_name', 'shift_type')->first();
        
        
       
        $this->year=now()->year;
        $this->month=now()->month;
            $this->from_date = Carbon::now()->subMonth()->startOfMonth()->toDateString();
            $this->start_date_for_insights = Carbon::now()->startOfMonth()->format('Y-m-d');
            $this->to_date = now()->toDateString();

            // $this->calculateAvgWorkingHrs($this->from_date, $this->to_date, $this->employee->emp_id);
            $fromDate = Carbon::createFromFormat('Y-m-d', $this->from_date);
            $toDate = Carbon::createFromFormat('Y-m-d', $this->to_date);
            $currentDate = Carbon::parse($this->from_date);
            $endDate = Carbon::parse($this->to_date);
            $totalHoursWorked = 0;
            $totalMinutesWorked = 0;
            $firstDateOfPreviousMonth = Carbon::now()->subMonth()->startOfMonth();

            // Get the current date of the current month
            $currentDateOfCurrentMonth = Carbon::now()->endOfDay();
           
          
           
            $startOfMonth = '2024-08-01';
            $endOfMonth = '2024-08-31';

            while ($currentDate->lte($endDate)) {
                $dateString = $currentDate->toDateString();

                // Get "IN" and "OUT" times for the current date
                $inTimes = SwipeRecord::where('emp_id', $this->selectedEmployeeId)
                    ->where('in_or_out', 'IN')
                    ->whereDate('created_at', $dateString)
                    ->pluck('swipe_time');

                $outTimes = SwipeRecord::where('emp_id', $this->selectedEmployeeId)
                    ->where('in_or_out', 'OUT')
                    ->whereDate('created_at', $dateString)
                    ->pluck('swipe_time');

                $totalDifferenceForDay = 0;
                // Calculate total time differences for the current date
                foreach ($inTimes as $index => $inTime) {
                    if (isset($outTimes[$index])) {
                        $inCarbon = Carbon::parse($inTime);
                        $outCarbon = Carbon::parse($outTimes[$index]);
                        $difference = $outCarbon->diffInSeconds($inCarbon);
                        $totalDifferenceForDay += $difference;
                        // $timeDifferences[$dateString][] = $difference;
                        // Store differences for each date
                    }
                }
                $currentDate->addDay(); // Move to the next day
            }

            // Optionally, calculate average time difference per day
            $averageDifferences = [];

            // foreach ($timeDifferences as $date => $differences) {

            //     if (count($differences) > 0) {
            //         $averageDifference = array_sum($differences) / count($differences);
            //         $averageDifferences[$date] = $averageDifference;
            //     }
            // }


            $this->updateModalTitle();
            $this->calculateTotalDays();
            $this->previousMonth = Carbon::now()->subMonth()->format('F');

            $swipeRecords = SwipeRecord::where('emp_id', $this->selectedEmployeeId)->get();

            // Group the swipe records by the date part only
            $groupedDates = $swipeRecords->groupBy(function ($record) {
                return Carbon::parse($record->created_at)->format('Y-m-d');
            });

            $this->distinctDates = $groupedDates->mapWithKeys(function ($records, $key) {
                $inRecord = $records->where('in_or_out', 'IN')->first();
                $outRecord = $records->where('in_or_out', 'OUT')->last();

                return [
                    $key => [
                        'in' => "IN",
                        'first_in_time' => optional($inRecord)->swipe_time,
                        'last_out_time' => optional($outRecord)->swipe_time,
                        'out' => "OUT",
                    ]
                ];
            });


            // Get the current date and store it in the $currentDate property
            $this->currentDate = date('d');
            $this->currentWeekday = date('D');
            $this->currentDate1 = date('d M Y');
            $this->swiperecords = SwipeRecord::all();
           
            $startOfMonth = Carbon::now()->startOfMonth();
            $today = Carbon::now();    
            $this->averageWorkHrsForCurrentMonth = $this->calculateAverageWorkHoursAndPercentage($startOfMonth->toDateString(),$today->toDateString());
            $this->percentageinworkhrsforattendance=$this->calculateDifferenceInAvgWorkHours(\Carbon\Carbon::now()->format('Y-m'));
            $this->generateCalendar();

       
    }

    
   
    public function closeRegularisationModal()
    {
        try {
            // Attempt to perform the action
            $this->showRegularisationDialog = false;
        } catch (\Exception $e) {
            // Handle the exception
            // You can log the error, show a user-friendly message, or handle it as needed
            // For example, you might log the exception message:
            error_log('Error closing regularisation modal: ' . $e->getMessage());

            // Optionally, you can display a user-friendly message or perform other actions
            // $this->showErrorMessage('An error occurred while closing the modal.');
        }
    }
    public function calculateDifferenceInAvgWorkHours($date)
    {
       
        $currentMonthStart = \Carbon\Carbon::parse($date . '-01')->startOfMonth()->toDateString();
        $currentMonthEnd = \Carbon\Carbon::parse($date)->endOfMonth()->toDateString(); // Today's date
        $previousMonthDate = \Carbon\Carbon::parse($date)->subMonth()->format('Y-m');
        
        // Log the initial dates
        Log::info('Initial dates:', [
            'date' => $date,
            'currentMonthStart' => $currentMonthStart,
            'currentMonthEnd' => $currentMonthEnd,
            'previousMonthDate' => $previousMonthDate
        ]);
    
        if (\Carbon\Carbon::parse($currentMonthEnd)->greaterThan(\Carbon\Carbon::today()) &&
            \Carbon\Carbon::parse($currentMonthEnd)->isSameMonth(\Carbon\Carbon::today()) &&
            \Carbon\Carbon::parse($currentMonthEnd)->isSameYear(\Carbon\Carbon::today())) {
            $currentMonthEnd = \Carbon\Carbon::today()->toDateString(); // Set to today's date if greater
            Log::info('Current month end adjusted to today:', ['currentMonthEnd' => $currentMonthEnd]);
        } elseif (\Carbon\Carbon::parse($currentMonthEnd)->greaterThan(\Carbon\Carbon::today())) {
            Log::warning('Current month end is greater than today. Returning \'-\'');
            return '-';
        }
    
        $previousMonthStart = \Carbon\Carbon::parse($previousMonthDate . '-01')->startOfMonth()->toDateString();
        $previousMonthEnd = \Carbon\Carbon::parse($previousMonthDate)->endOfMonth()->toDateString(); // End of previous month
    
        // Log previous month dates
        Log::info('Previous month dates:', [
            'previousMonthStart' => $previousMonthStart,
            'previousMonthEnd' => $previousMonthEnd
        ]);
    
        if (\Carbon\Carbon::parse($previousMonthEnd)->greaterThan(\Carbon\Carbon::today()) &&
            \Carbon\Carbon::parse($previousMonthEnd)->isSameMonth(\Carbon\Carbon::today()) &&
            \Carbon\Carbon::parse($previousMonthEnd)->isSameYear(\Carbon\Carbon::today())) {
            $previousMonthEnd = \Carbon\Carbon::today()->toDateString(); // Set to today's date if greater
            Log::info('Previous month end adjusted to today:', ['previousMonthEnd' => $previousMonthEnd]);
        } elseif (\Carbon\Carbon::parse($previousMonthEnd)->greaterThan(\Carbon\Carbon::today())) {
            Log::warning('Previous month end is greater than today. Returning \'-\'');
            return '-';
        }
    
        // Log before calculation
        Log::info('Calling calculateAverageWorkHoursAndPercentage for current and previous months', [
            'currentMonthStart' => $currentMonthStart,
            'currentMonthEnd' => $currentMonthEnd,
            'previousMonthStart' => $previousMonthStart,
            'previousMonthEnd' => $previousMonthEnd
        ]);
    
        // Calculate average work hours for current and previous months
        $avgWorkHoursCurrentMonth = $this->calculateAverageWorkHoursAndPercentage($currentMonthStart, $currentMonthEnd);
        $avgWorkHoursPreviousMonth = $this->calculateAverageWorkHoursAndPercentage($previousMonthStart, $previousMonthEnd);
        
        // Log average work hours
        Log::info('Average work hours:', [
            'avgWorkHoursCurrentMonth' => $avgWorkHoursCurrentMonth,
            'avgWorkHoursPreviousMonth' => $avgWorkHoursPreviousMonth
        ]);
    
        // Convert the average work hours (HH:MM) to total minutes for comparison
        list($currentMonthHours, $currentMonthMinutes) = explode(':', $avgWorkHoursCurrentMonth);
        list($previousMonthHours, $previousMonthMinutes) = explode(':', $avgWorkHoursPreviousMonth);
    
        $currentMonthTotalMinutes = ($currentMonthHours * 60) + $currentMonthMinutes;
        $previousMonthTotalMinutes = ($previousMonthHours * 60) + $previousMonthMinutes;
    
        // Log total minutes
        Log::info('Total minutes:', [
            'currentMonthTotalMinutes' => $currentMonthTotalMinutes,
            'previousMonthTotalMinutes' => $previousMonthTotalMinutes
        ]);
    
        
        // Calculate the difference in minutes
        $differenceInMinutes = $currentMonthTotalMinutes - $previousMonthTotalMinutes;
       
        Log::info('Difference in minutes:', ['differenceInMinutes' => $differenceInMinutes]);
    
        if ($this->previousMonthTotalMinutes != 0) {
            $this->percentageDifference = ($differenceInMinutes / $this->previousMonthTotalMinutes) * 100;
        } else {
            $this->percentageDifference = 0;
        }
    
        // Convert the difference back to hours and minutes
        $hoursDifference = intdiv($differenceInMinutes, 60);
        $minutesDifference = $differenceInMinutes % 60;
    
        Log::info('Final difference:', [
            'hoursDifference' => $hoursDifference,
            'minutesDifference' => $minutesDifference,
            'percentageDifference' => $this->percentageDifference
        ]);
    
        return $this->percentageDifference;
    }
    

    public function toggleSession1Fields()
    {
        try {
            $this->session1 = !$this->session1;
            $this->session1ArrowDirection = ($this->session1) ? 'left' : 'right';
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error in toggleSession1Fields method: ' . $e->getMessage());

            // Optionally, you can set some default values or handle the error in a user-friendly way
            $this->session1 = false;
            $this->session1ArrowDirection = 'right';

            // You can also set a session message or an error message to inform the user
            session()->flash('error', 'An error occurred while toggling session fields. Please try again later.');
        }
    }
    //This function will help us to toggle the arrow present in session fields
    public function toggleSession2Fields()
    {
        try {
            $this->session2 = !$this->session2;
            $this->session2ArrowDirection = ($this->session2) ? 'left' : 'right';
            // dd($this->session1);
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error in toggleSession2Fields method: ' . $e->getMessage());

            // Optionally, you can set some default values or handle the error in a user-friendly way
            $this->session2 = false;
            $this->session2ArrowDirection = 'right';

            // You can also set a session message or an error message to inform the user
            session()->flash('error', 'An error occurred while toggling session fields. Please try again later.');
        }
    }
    public  $averageWorkingHours, $percentageOfHoursWorked, $yearA, $monthA;

    public function calculateMetrics()
    {
        $employeeId = $this->selectedEmployeeId;

        // Get the current date

        // Specify the current month and year
        $swipes = SwipeRecord::where('emp_id', $employeeId)
            ->whereYear('created_at', $this->year)
            ->whereMonth('created_at', $this->month)
            ->orderBy('swipe_time')
            ->get();


        // Initialize arrays to store in and out swipes
        $inSwipes = [];
        $outSwipes = [];

        // Process swipe records
        foreach ($swipes as $swipe) {
            $date = Carbon::parse($swipe->swipe_time)->toDateString();
            if ($swipe->in_or_out === 'IN') {
                if (!isset($inSwipes[$date])) {
                    $inSwipes[$date] = [];
                }
                $inSwipes[$date][] = $swipe;
            } elseif ($swipe->in_or_out === 'OUT') {
                if (!isset($outSwipes[$date])) {
                    $outSwipes[$date] = [];
                }
                $outSwipes[$date][] = $swipe;
            }
        }

        // Calculate total hours worked in the month
        $totalHoursWorked = 0;
        foreach ($inSwipes as $date => $inSwipeArray) {
            if (isset($outSwipes[$date])) {
                foreach ($inSwipeArray as $index => $inSwipe) {
                    if (isset($outSwipes[$date][$index])) {
                        $inTime = Carbon::parse($inSwipe->swipe_time);
                        $outTime = Carbon::parse($outSwipes[$date][$index]->swipe_time);
                        $totalHoursWorked += $outTime->diffInHours($inTime);
                    }
                }
            }
        }

        // Calculate the number of working days in the current month
        $startDate = Carbon::create($this->year, $this->month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $workingDaysCount = 0;

        while ($startDate->lte($endDate)) {
            if ($startDate->isWeekday()) {
                $workingDaysCount++;
            }
            $startDate->addDay();
        }

        // Define the standard daily hours
        $standardDailyHours = 9;

        // Calculate the standard monthly hours
        $standardMonthlyHours = $standardDailyHours * $workingDaysCount;

        // Calculate the average working hours for the month
        $this->averageWorkingHours = $workingDaysCount > 0 ? $totalHoursWorked / $workingDaysCount : 0;

        // Calculate the percentage of hours worked relative to the standard monthly hours
        $this->percentageOfHoursWorked = $standardMonthlyHours > 0 ? ($totalHoursWorked / $standardMonthlyHours) * 100 : 0;
    }

    


    public function showTable()
    {
        try {
            // Your code that might throw an exception
            $this->defaultfaCalendar = 0;
        } catch (\Exception $e) {
            // Handle the exception
            // You can log the error or set an error message
            Log::error('Error in showTable method: ' . $e->getMessage());
            // Optionally, you can set a user-friendly message to be displayed
            $errorMessage = 'An error occurred while updating the calendar. Please try again later.';
        }
    }

    public function showBars()
    {
        try {
            $this->defaultfaCalendar = 1;
            $this->showMessage = false;
        } catch (\Exception $e) {
            Log::error('Error in showBars method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while showing the bars. Please try again later.');
        }
    }
    //This function will help us to calculate the number of public holidays in a particular month
    protected function getPublicHolidaysForMonth($year, $month)
    {
        try {
            return HolidayCalendar::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error in getPublicHolidaysForMonth method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while fetching public holidays. Please try again later.');
            return collect(); // Return an empty collection to handle the error gracefully
        }
    }

    public function showlargebox($k)
    {
        try {
            $this->k1 = $k;
            $this->dispatchBrowserEvent('refreshModal', ['k1' => $this->k1]);
        } catch (\Exception $e) {
            Log::error('Error in showlargebox method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while showing the large box. Please try again later.');
        }
    }
    private function isEmployeeRegularisedOnDate($date)
    {
        try {
            $employeeId = $this->selectedEmployeeId;
            return SwipeRecord::where('emp_id', $employeeId)->whereDate('created_at', $date)->where('is_regularized', 1)->exists();
        } catch (\Exception $e) {
            Log::error('Error in isEmployeePresentOnDate method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while checking employee presence. Please try again later.');
            return false; // Return false to handle the error gracefully
        }
    }
    
    private function isEmployeePresentOnDate($date)
    {
        try {
            $employeeId = $this->selectedEmployeeId;
            Log::info('Checking employee presence for the following data:', [
                'employeeId' => $employeeId,
                'date' => $date
            ]);
            return SwipeRecord::where('emp_id', $employeeId)->whereDate('created_at', $date)->exists();

        } catch (\Exception $e) {
            Log::error('Error in isEmployeePresentOnDate method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while checking employee presence. Please try again later.');
            return false; // Return false to handle the error gracefully
        }
    }
    //This function will help us to check if the employee is on leave for this particular date or not
    private function isEmployeeLeaveOnDate($date, $employeeId)
    {
        try {
            $employeeId = $this->selectedEmployeeId;
            Log::info('Checking employee leave for the following data:', [
                'employeeId' => $employeeId,
                'date' => $date
            ]);

            return LeaveRequest::where('emp_id', $employeeId)
                ->where('leave_status', 2)
                ->where(function ($query) use ($date) {
                    $query->whereDate('from_date', '<=', $date)
                        ->whereDate('to_date', '>=', $date);
                })
                ->exists();
        } catch (\Exception $e) {
            Log::error('Error in isEmployeeLeaveOnDate method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while checking employee leave. Please try again later.');
            return false; // Return false to handle the error gracefully
        }
    }
    private function caluclateNumberofLeaves($startDate, $endDate, $employeeId)
    {
        $countofleaves = 0;
        $currentDate = $startDate->copy();


        while ($currentDate->lt($endDate)) {
            if ($this->isEmployeeLeaveOnDate($currentDate, $employeeId)) {
                $countofleaves++;
            }
            $currentDate->addDay();
        }

        return $countofleaves;
    }
    //This function will help us to check the leave type of employee
    private function getLeaveType($date, $employeeId)
    {
        try {
            return LeaveRequest::where('emp_id', $employeeId)
                ->whereDate('from_date', '<=', $date)
                ->whereDate('to_date', '>=', $date)
                ->value('leave_type');
        } catch (\Exception $e) {
            Log::error('Error in getLeaveType method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while fetching leave type. Please try again later.');
            return null; // Return null to handle the error gracefully
        }
    }
    private function isDateRegularized($date, $employeeId)
    {
        $records = RegularisationDates::where('emp_id', $employeeId)->get();

        foreach ($records as $record) {
            $regularisationEntries = json_decode($record->regularisation_entries, true);
            foreach ($regularisationEntries as $entry) {
                if ($entry['date'] === $date) {
                    return true;
                }
            }
        }

        return false;
    }
    public function generateCalendar()
    {
        try {
            $employeeId = $this->selectedEmployeeId;
           
            $firstDay = Carbon::create($this->year, $this->month, 1);
           
            $daysInMonth = $firstDay->daysInMonth;
            
            $today = now();
            
            $calendar = [];
            $dayCount = 1;
            $publicHolidays = $this->getPublicHolidaysForMonth($this->year, $this->month);
        
            // Calculate the first day of the week for the current month
            $firstDayOfWeek = $firstDay->dayOfWeek;
           
            // Calculate the starting date of the previous month
            $startOfPreviousMonth = $firstDay->copy()->subMonth();
            
            // Fetch holidays for the previous month
            $publicHolidaysPreviousMonth = $this->getPublicHolidaysForMonth(
                $startOfPreviousMonth->year,
                $startOfPreviousMonth->month
            );

            // Calculate the last day of the previous month
            $lastDayOfPreviousMonth = $firstDay->copy()->subDay();

            for ($i = 0; $i < ceil(($firstDayOfWeek + $daysInMonth) / 7); $i++) {
                $week = [];
            
                for ($j = 0; $j < 7; $j++) {
                    if ($i === 0 && $j < $firstDay->dayOfWeek) {
                        // Add the days of the previous month
                        $previousMonthDays = $lastDayOfPreviousMonth->copy()->subDays($firstDay->dayOfWeek - $j - 1);
                        $week[] = [
                            'day' => $previousMonthDays->day,
                            'isToday' => false,
                            'isPublicHoliday' => in_array($previousMonthDays->toDateString(), $publicHolidaysPreviousMonth->pluck('date')->toArray()),
                            'isCurrentMonth' => false,
                            'isPreviousMonth' => true,
                            'isRegularised' => false,
                            'backgroundColor' => '',
                            'status' => '',
                            'onleave' => ''
                        ];
                    } elseif ($dayCount <= $daysInMonth) {
                        $isToday = $dayCount === $today->day && $this->month === $today->month && $this->year === $today->year;
                        $isPublicHoliday = in_array(
                            Carbon::create($this->year, $this->month, $dayCount)->toDateString(),
                            $publicHolidays->pluck('date')->toArray()
                        );

                        $backgroundColor = $isPublicHoliday ? 'background-color: IRIS;' : '';

                        $date = Carbon::create($this->year, $this->month, $dayCount)->toDateString();
                        $isregularised = $this->isEmployeeRegularisedOnDate($date);
                        // Check if the employee is absent
                        $isAbsent = !$this->isEmployeePresentOnDate($date);
                        $isonLeave = $this->isEmployeeLeaveOnDate($date, $employeeId);
                        $leaveType = $this->getLeaveType($date, $employeeId);
                        if ($isonLeave) {
                            $leaveType = $this->getLeaveType($date, $employeeId);

                            switch ($leaveType) {
                                case 'Casual Leave Probation':
                                    $status = 'CLP'; // Casual Leave Probation
                                    break;
                                case 'Sick Leave':
                                    $status = 'SL'; // Sick Leave
                                    break;
                                case 'Loss Of Pay':
                                    $status = 'LOP'; // Loss of Pay
                                    break;
                                case 'Casual Leave':
                                    $status = 'CL'; // Loss of Pay
                                    break;
                                case 'Marriage Leave':
                                    $status = 'ML'; // Loss of Pay
                                    break;
                                case 'Paternity Leave':
                                    $status = 'PL'; // Loss of Pay
                                    break;
                                case 'Maternity Leave':
                                    $status = 'MTL'; // Loss of Pay
                                    break;        
                                default:
                                    $status = 'L'; // Default to 'L' if the leave type is not recognized
                                    break;
                            }
                        } else {
                            // Employee is not on leave, check for absence or presence
                            $isAbsent = !$this->isEmployeePresentOnDate($date);

                            // Set the status based on presence
                            $status = $isAbsent ? 'A' : 'P';
                        }
                        // Set the status based on presence
                        $week[] = [
                            'day' => $dayCount,
                            'isToday' => $isToday,
                            'isPublicHoliday' => $isPublicHoliday,
                            'isCurrentMonth' => true,
                            'isRegularised' => $isregularised,
                            'isPreviousMonth' => false,
                            'backgroundColor' => $backgroundColor,
                            'onleave'=>$isonLeave,
                            'status' => $status,
                        ];

                        $dayCount++;
                    } else {
                        $week[] = [
                            'day' => $dayCount - $daysInMonth,
                            'isToday' => false,
                            'isPublicHoliday' => in_array($lastDayOfPreviousMonth->copy()->addDays($dayCount - $daysInMonth)->toDateString(), $this->getPublicHolidaysForMonth($startOfPreviousMonth->year, $startOfPreviousMonth->month)->pluck('date')->toArray()),
                            'isCurrentMonth' => false,
                            'isRegularised' => false,
                            'isNextMonth' => true,
                            'backgroundColor' => '',
                            'onleave'=>'false',
                            'status' => '',
                        ];
                        $dayCount++;
                    }
                }
                $calendar[] = $week;
            }

            $this->calendar = $calendar;
            
        } catch (\Exception $e) {
            Log::error('Error in generateCalendar method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while generating the calendar. Please try again later.');
            $this->calendar = []; // Set calendar to empty array in case of error
        }
    }
    public function updateDate($date1)
    {
        try {
            $parsedDate = Carbon::parse($date1);

            $this->dateToCheck = $date1;

            if ($parsedDate->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {

                $this->changeDate = 1;
            }
        } catch (\Exception $e) {
            Log::error('Error in updateDate method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while updating the date. Please try again later.');
        }
    }
    //This function will help us to check whether the employee is absent 'A' or present 'P'
    public function dateClicked($date1)
    {
        try {

            $date1 = trim($date1);

            $this->selectedDate = $this->year . '-' . $this->month . '-' . str_pad($date1, 2, '0', STR_PAD_LEFT);

            $isSwipedIn = SwipeRecord::whereDate('created_at', $date1)->where('in_or_out', 'In')->exists();
            $isSwipedOut = SwipeRecord::whereDate('created_at', $date1)->where('in_or_out', 'Out')->exists();

            if (!$isSwipedIn) {
                // Employee did not swipe in
                $this->selectedDate = $date1;
                $this->status = 'A';
            } elseif (!$isSwipedOut) {
                // Employee swiped in but not out
                $this->selectedDate = $date1;
                $this->status = 'P';
            }
            $this->updateDate($date1);
            $this->dateclicked = $date1;
        } catch (\Exception $e) {
            Log::error('Error in dateClicked method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while processing the date click. Please try again later.');
        }
    }
    public function updatesearchTerm()
    {
        $this->searchTerm=$this->searchTerm;
    }
 
    public function updateEmployeeType()
    {
        // Handle the change in employee type
        $this->employeeType = $this->employeeType;
        
       
        
    }
    public function calculateAverageWorkHoursAndPercentage($startDate, $endDate)
{
    $employeeId = $this->selectedEmployeeId;
    Log::info("Employee ID: {$employeeId}");
    
    // Retrieve swipe records within the given date range
    $records = SwipeRecord::where('emp_id', $employeeId)
        ->whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<', $endDate)
        ->orderBy('created_at')
        ->get();
    Log::info("Swipe Records Count: " . $records->count());

    // Group swipes by date
    $dailySwipes = $records->groupBy(function ($swipe) {
        return Carbon::parse($swipe->created_at)->toDateString();
    });
    Log::info("Grouped Daily Swipes: " . $dailySwipes->keys());

    // Get leave requests for the employee within the date range
    $leaveRequests = LeaveRequest::where('emp_id', $employeeId)
        ->where('leave_applications.leave_status', 2)
        ->where(function ($query) use ($startDate, $endDate) {
            $query->whereDate('from_date', '<=', $endDate)
                  ->whereDate('to_date', '>=', $startDate);
        })
        ->join('status_types', 'status_types.status_code', '=', 'leave_applications.leave_status')
        ->select('leave_applications.*', 'status_types.status_name')
        ->get();
    Log::info("Leave Requests Count: " . $leaveRequests->count());

    $totalMinutes = 0;
    $workingDaysCount = 0;

    // Determine if the current month is involved
    $today = Carbon::now();
    $isCurrentMonth = Carbon::parse($startDate)->isSameMonth($today) && Carbon::parse($endDate)->isSameMonth($today);
    Log::info("Is Current Month: " . ($isCurrentMonth ? 'Yes' : 'No'));

    // Calculate the total working days in the date range
    $currentDate = Carbon::parse($startDate);
    $endDate = Carbon::parse($endDate);

    while ($currentDate <= $endDate) {
        if ($isCurrentMonth && $currentDate->isSameDay($today)) {
            $currentDate->addDay();
            continue;
        }

        $isWeekend = $currentDate->isWeekend();
        $isHoliday = HolidayCalendar::where('date', $currentDate->toDateString())->exists();
        $isOnLeave = $leaveRequests->contains(function ($leaveRequest) use ($currentDate) {
            return $currentDate->between($leaveRequest->from_date, $leaveRequest->to_date);
        });

        if (!$isWeekend && !$isHoliday && !$isOnLeave) {
            $workingDaysCount++;
        }
        Log::info("Date: {$currentDate->toDateString()}, Is Weekend: {$isWeekend}, Is Holiday: {$isHoliday}, Is On Leave: {$isOnLeave}");
        $currentDate->addDay();
    }

    foreach ($dailySwipes as $date => $swipesForDay) {
        $inTime = null;
        $dayMinutes = 0;
        $carbonDate = Carbon::parse($date);
        $isWeekend = $carbonDate->isWeekend();
        $isHoliday = HolidayCalendar::where('date', $carbonDate->toDateString())->exists();
        $isOnLeave = $leaveRequests->contains(function ($leaveRequest) use ($carbonDate) {
            return $carbonDate->between($leaveRequest->from_date, $leaveRequest->to_date);
        });

        if (!$isWeekend && !$isHoliday && !$isOnLeave) {
            foreach ($swipesForDay as $swipe) {
                if ($swipe->in_or_out === 'IN') {
                    $inTime = Carbon::parse($swipe->swipe_time);
                }

                if ($swipe->in_or_out === 'OUT' && $inTime) {
                    $outTime = Carbon::parse($swipe->swipe_time);
                    $dayMinutes += $inTime->diffInMinutes($outTime);
                    $inTime = null;
                }
            }

            if ($inTime && $dayMinutes === 0) {
                $dayMinutes = 0;
            }

            $totalMinutes += $dayMinutes;
        }
        Log::info("Date: {$date}, Day Minutes: {$dayMinutes}, Total Minutes So Far: {$totalMinutes}");
    }

    if ($workingDaysCount > 0) {
        $averageMinutes = $totalMinutes / $workingDaysCount;
    } else {
        $averageMinutes = 0;
    }

    $hours = intdiv($averageMinutes, 60);
    $minutes = $averageMinutes % 60;

    $averageWorkHours = sprintf('%02d:%02d', $hours, $minutes);
    Log::info("Average Work Hours: {$averageWorkHours}");
    
    return $averageWorkHours;
}
    public function getEmployeesByType()
    {
        $emptype=$this->employeeType;
        $query = EmployeeDetails::query();
        // Example logic to fetch employees based on the selected type
        switch ($emptype) {
            case 'current':
                $query->where('employee_status', 'active');
                break;
            case 'past':
                $query->where('employee_status', 'terminated')->orWhere('employee_status','resigned');
                break;
            case 'interns':
                $query->where('job_role', 'intern');
                break;
            default:
                // If "all" is selected, no additional filtering for status
                break;
        }
        if (!empty($this->searchTerm)) {
            $query->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('emp_id', 'like', '%' . $this->searchTerm . '%');
            });
        }
    
        // Get the filtered employees
        return $query->get();
    
    }
    // This method runs when the selected employee type changes
   

    

    public function updatedToDate($value)
    {
        try {
            // Additional logic if needed when to_date is updated
            $this->to_date = $value;
            $this->updateModalTitle();
        } catch (\Exception $e) {
            Log::error('Error in updatedToDate method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while updating the to date. Please try again later.');
        }
    }
    public function openlegend()
    {
        $this->legend = !$this->legend;
    }
    private function calculateNumberofHolidays($startDate, $endDate)
    {
        $holidayCount = 0;
        $currentDate = $startDate->copy();
        while ($currentDate->lt($endDate)) {
            $holidayexists = HolidayCalendar::where('date', Carbon::parse($currentDate)->format('Y-m-d'))->exists();
            if ($holidayexists == true) {
                $holidayCount++;
            }
            $currentDate->addDay();
            $holidayexists = false;
        }
        return $holidayCount;
    }
    private function calculatetotalLateInSwipes($startDate, $endDate)
    {
        // Parse start and end dates using Carbon
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $lateSwipeCount = 0;
        $firstInCount=0;
        $totalFirstInSeconds = 0;
        

        Log::info('Start Date: ' . $startDate->toDateString() . ', End Date: ' . $endDate->toDateString());
    
        // Iterate through the date range
        while ($startDate->lt($endDate)) {
            $tempStartDate = $startDate->toDateString();
            
            // Check if the date is a holiday, weekend, or employee is on leave
            $isweekend = $startDate->isWeekend();
            
            $isHoliday = HolidayCalendar::whereDate('date', $tempStartDate)->exists();
           
            $isOnLeave = $this->isEmployeeLeaveOnDate($tempStartDate, $this->selectedEmployeeId);
            $isPresent = SwipeRecord::where('emp_id', $this->selectedEmployeeId)->where('in_or_out','IN')->whereDate('created_at', $tempStartDate)->first();
            
            
            // Log the status of the current day
            Log::info("Date: $tempStartDate, IsHoliday: " . ($isHoliday ? 'Yes' : 'No') . 
                      ", IsWeekend: " . ($isweekend ? 'Yes' : 'No') . 
                      ", IsOnLeave: " . ($isOnLeave ? 'Yes' : 'No') . 
                      ", IsPresent: " . (!empty($isPresent) ? 'Yes' : 'No'));
    
            // If not a holiday, weekend, or leave day, and the employee is present
            if (!$isHoliday && !$isweekend && !$isOnLeave && !empty($isPresent)) {
                // Check for late swipes
                 $firstInCount++;
                 try {
                    $swipeTime = Carbon::createFromFormat('H:i:s', $isPresent->swipe_time);
                    $limitTime = Carbon::createFromTime(10, 0, 0);
                } catch (\Exception $e) {
                    $swipeTime = Carbon::createFromFormat('H:i', $isPresent->swipe_time);
                    $limitTime = Carbon::createFromTime(10, 0);
                }
                $totalFirstInSeconds += $swipeTime->diffInSeconds(Carbon::createFromTime(0, 0, 0));
                 if ($swipeTime->greaterThan($limitTime)) {
                    $lateSwipeExists=true;
                } else {
                    // Swipe time is 10:00 AM or later
                    $lateSwipeExists=false;
                }
                // Log the late swipe check
                Log::info("Late Swipe Exists: " . ($lateSwipeExists ? 'Yes' : 'No') . " on Date: $tempStartDate"." on Time: $swipeTime");
    
                // Increment late swipe count if a late swipe is found
                if ($lateSwipeExists==true) {
                    $lateSwipeCount++;
                    Log::info("Late Swipe Count Incremented: $lateSwipeCount");
                }
                Log::info(message: "First In Count Incremented: $firstInCount");
            }
           
            // Move to the next day
            $startDate->addDay();
        }
    
        // Log the final late swipe count
        Log::info("Total Late Swipes: $lateSwipeCount");
        if ($firstInCount > 0) {
            $averageFirstInSeconds = $totalFirstInSeconds / $firstInCount;
            $averageFirstInTime = Carbon::createFromTime(0, 0, 0)->addSeconds($averageFirstInSeconds);
        } else {
            $averageFirstInTime = null;  // No valid first in records
        }
    
        // Log results
        Log::info("Total First In Count: $firstInCount, Late Swipe Count: $lateSwipeCount");
        Log::info("Average First In Time: " . ($averageFirstInTime ? $averageFirstInTime->format('H:i:s') : 'N/A'));
    
        return [
            'averageFirstInTime' => $averageFirstInTime ? $averageFirstInTime->format('H:i:s') : 'N/A',
            'lateSwipeCount' => $lateSwipeCount,
           
        ];
    }
    private function calculatetotalEarlyOutSwipes($startDate, $endDate)
    {
        log::info('early out method called');
        // Parse start and end dates using Carbon
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $earlyOutCount = 0;
        $lastOutCount=0;
        $totalLastOutSeconds=0;
        Log::info('Start Date: ' . $startDate->toDateString() . ', End Date: ' . $endDate->toDateString());
   
        // Iterate through the date range
        while ($startDate->lt($endDate)) {
            $tempStartDate = $startDate->toDateString();
           
            // Check if the date is a holiday, weekend, or employee is on leave
            $isHoliday = HolidayCalendar::whereDate('date', $tempStartDate)->exists();
            $isweekend = $startDate->isWeekend();
            $isOnLeave = $this->isEmployeeLeaveOnDate($tempStartDate, $this->selectedEmployeeId);
            $isPresent = SwipeRecord::where('emp_id', $this->selectedEmployeeId)->where('in_or_out','OUT')->whereDate('created_at', $tempStartDate)->first();
       
            if(empty($isPresent))
            {
                $isPresent = SwipeRecord::where('emp_id', $this->selectedEmployeeId)->where('in_or_out','IN')->whereDate('created_at', $tempStartDate)->first();   
            }
            
           
            // Log the status of the current day
            Log::info("Date: $tempStartDate, IsHoliday: " . ($isHoliday ? 'Yes' : 'No') .
                      ", IsWeekend: " . ($isweekend ? 'Yes' : 'No') .
                      ", IsOnLeave: " . ($isOnLeave ? 'Yes' : 'No') .
                      ", IsPresent: " . (!empty($isPresent) ? 'Yes' : 'No'));
   
            // If not a holiday, weekend, or leave day, and the employee is present
            if (!$isHoliday && !$isweekend && !$isOnLeave && !empty($isPresent)) {
                // Check for late swipes
                $lastOutCount++;
                try {
                    $swipeTime = Carbon::createFromFormat('H:i:s', $isPresent->swipe_time);
                    $limitTime = Carbon::createFromTime(19, 0, 0);
                } catch (\Exception $e) {
                    $swipeTime = Carbon::createFromFormat('H:i', $isPresent->swipe_time);
                    $limitTime = Carbon::createFromTime(19, 0);
                }
                $totalLastOutSeconds += $swipeTime->diffInSeconds(Carbon::createFromTime(0, 0, 0));
                 if ($swipeTime->lessThan($limitTime)) {
                    $earlyOutExists=true;
                } else {
                    // Swipe time is 10:00 AM or later
                    $earlyOutExists=false;
                }
                   
 
                // Log the late swipe check
                Log::info("Early Out  Exists: " . ($earlyOutExists ? 'Yes' : 'No') . " on Date: $tempStartDate");
   
                // Increment late swipe count if a late swipe is found
                if ($earlyOutExists) {
                    $earlyOutCount++;
                    Log::info("Early Out Count Incremented: $earlyOutCount");
                }
            }
           
            // Move to the next day
            $startDate->addDay();
           
        }
        if ($lastOutCount > 0) {
            $averageLastOutSeconds = $totalLastOutSeconds / $lastOutCount;
            $averageLastOutTime = Carbon::createFromTime(0, 0, 0)->addSeconds($averageLastOutSeconds);
        } else {
            $averageLastOutTime = null;  // No valid first in records
        }
        return [
            'averageLastOutTime' => $averageLastOutTime ? $averageLastOutTime->format('H:i:s') : 'N/A',
            'EarlyOutCount' => $earlyOutCount,
           
        ];
    }
    private function calculateTotalNumberOfLeaves($startDate, $endDate)
    {
        $leaveCount = 0;
    
        Log::info('Starting leave calculation from ' . $startDate->toDateString() . ' to ' . $endDate->toDateString());
    
        // Iterate through the date range
        while ($startDate->lt($endDate)) {
            $tempStartDate = $startDate->toDateString();
    
            // Check if the current date is a holiday
            $isHoliday = HolidayCalendar::where('date', $tempStartDate)->exists();
    
            // Skip weekends (Saturday and Sunday) or holidays
            if ($startDate->isWeekend()) {
                Log::info('Skipping weekend: ' . $tempStartDate);
            } elseif ($isHoliday) {
                Log::info('Skipping holiday: ' . $tempStartDate);
            } else {
                // Log current date and status
                Log::info('Checking date: ' . $tempStartDate);
    
                // Check if employee is on leave on this date
                $isOnLeave = $this->isEmployeeLeaveOnDate($tempStartDate,$this->selectedEmployeeId);
                Log::info('Is on leave: ' . ($isOnLeave ? 'Yes' : 'No'));
    
                if ($isOnLeave) {
                    $leaveCount++;
                }
            }
    
            // Move to the next day
            $startDate->addDay();
        }
    
        Log::info('Total leave count (excluding weekends and holidays): ' . $leaveCount);
    
        return $leaveCount;
    }
   
    private function calculateTotalNumberOfAbsents($startDate, $endDate)
    {
        $absentDays = 0;
        
        // Add a log entry for the start and end date
        Log::info('Calculating total number of absents between: ' . $startDate->format('Y-m-d') . ' and ' . $endDate->format('Y-m-d'));
    
        // Loop through each date between start and end date
        for ($date = $startDate->copy(); $date->lt($endDate); $date->addDay()) {
            
            // Log the current date being checked
            Log::info('Checking for absence on: ' . $date->format('Y-m-d'));
            if(!$date->isWeekend())
            {
                $holiday=HolidayCalendar::where('date',$date)->exists();
                if(!$holiday)
                {
                    $isOnLeave=$this->isEmployeeLeaveOnDate($date->format('Y-m-d'),$this->selectedEmployeeId);
                    if(!$isOnLeave)
                    {
                        $isAbsent = !$this->isEmployeePresentOnDate($date->format('Y-m-d'));
                        Log::info('Is employee absent on ' . $date->format('Y-m-d') . '? ' . ($isAbsent ? 'Yes' : 'No'));
                        if ($isAbsent) {
                            $absentDays++;
                            // Log the increment of absent days
                            Log::info('Absent days count incremented to: ' . $absentDays);
                        }
                    }
                }
                
            }
            // Check if the employee is absent on the current date
            
    
            // Log the result of the absence check
        
            
            
        }
    
        // Log the final absent days count
        Log::info('Total number of absent days: ' . $absentDays);
        
        return $absentDays;
    }
    
    private function updateModalTitle()
    {
        try {
            // Format the dates and update the modal title
            
            $formattedFromDate = Carbon::parse($this->start_date_for_insights)->format('Y-m-d');
            $formattedToDate = Carbon::parse($this->to_date)->format('Y-m-d');
            if ($formattedFromDate > $formattedToDate) {
                $formattedFromDateForModalTitle = Carbon::parse($this->start_date_for_insights)->format('d M');
                $formattedToDateForModalTitle = Carbon::parse($this->to_date)->format('d M');
                $this->modalTitle = "Insights for Attendance Period $formattedFromDateForModalTitle - $formattedToDateForModalTitle";
                $this->addError('date_range', 'The start date cannot be greater than the end date.');
                return; // Stop execution if validation fails
            }
            if ($formattedFromDate >=  Carbon::today()->format('Y-m-d') && $formattedToDate >=  Carbon::today()->format('Y-m-d')) {
                $formattedFromDateForModalTitle = Carbon::parse($this->start_date_for_insights)->format('d M');
                $formattedToDateForModalTitle = Carbon::parse($this->to_date)->format('d M');
                $this->modalTitle = "Insights for Attendance Period $formattedFromDateForModalTitle - $formattedToDateForModalTitle";
                // Set values to '-' and average work hours to '00:00'
                $this->totalWorkingDays='-';
                $this->totalLateInSwipes = '-';
                $this->totalnumberofEarlyOut = '-';
                $this->totalnumberofLeaves = '-';
                $this->totalnumberofAbsents = '-';
                $this->averageWorkHoursForModalTitle = '-';
                $this->avergageFirstInTime='N/A';
                $this->averageLastOutTime='N/A';
                return; // Stop execution after setting values
            }
            if ($formattedToDate >  Carbon::today()->format('Y-m-d')) {
                $formattedFromDateForModalTitle = Carbon::parse($this->start_date_for_insights)->format('d M');
                $formattedToDateForModalTitle = Carbon::parse($this->to_date)->format('d M');
                $this->modalTitle = "Insights for Attendance Period $formattedFromDateForModalTitle - $formattedToDateForModalTitle";
                // Set values to '-' and average work hours to '00:00'
                $this->totalWorkingDays='-';
                $this->totalLateInSwipes = '-';
                $this->totalnumberofEarlyOut = '-';
                $this->totalnumberofLeaves = '-';
                $this->totalnumberofAbsents = '-';
                $this->averageWorkHoursForModalTitle = '-';
                $this->avergageFirstInTime='N/A';
                $this->averageLastOutTime='N/A';
                return; // Stop execution after setting values
            }
            
            $fromDatetemp = Carbon::parse($this->start_date_for_insights);
            $toDatetemp = Carbon::parse($this->to_date);
            $formattedFromDateForModalTitle = Carbon::parse($this->start_date_for_insights)->format('d M');
            $formattedToDateForModalTitle = Carbon::parse($this->to_date)->format('d M');
            $this->modalTitle = "Insights for Attendance Period $formattedFromDateForModalTitle - $formattedToDateForModalTitle";
            
            $this->totalWorkingDays = $this->calculateTotalWorkingDays($fromDatetemp, $toDatetemp);
            $this->totalnumberofAbsents = $this->calculateTotalNumberOfAbsents(Carbon::parse($this->start_date_for_insights), Carbon::parse($this->to_date));
            $this->totalnumberofLeaves = $this->calculateTotalNumberOfLeaves(Carbon::parse($this->start_date_for_insights), Carbon::parse($this->to_date));
                              
            $insights=$this->calculatetotalLateInSwipes(Carbon::parse($this->start_date_for_insights), Carbon::parse($this->to_date));
            $outsights=$this->calculatetotalEarlyOutSwipes(Carbon::parse($this->start_date_for_insights), Carbon::parse($this->to_date));
            $this->totalLateInSwipes = $insights['lateSwipeCount'];
            $this->totalnumberofEarlyOut=$outsights['EarlyOutCount'];
            $this->averageLastOutTime=$outsights['averageLastOutTime'];
            $this->avergageFirstInTime=$insights['averageFirstInTime'];
            
            $this->averageWorkHoursForModalTitle=$this->calculateAverageWorkHoursAndPercentage(Carbon::parse($this->start_date_for_insights), Carbon::parse($this->to_date));         
          
           
           
          
           
            // $this->totalnumberofLeaves = $this->calculateTotalNumberOfLeaves($fromDatetemp, $toDatetemp);
           
         
           
            // $timePattern = '/^\d{2}:\d{2}:\d{2}$/';    
            // if (!empty($this->averageLastOutTime) && !empty($this->avergageFirstInTime) && 
            //         preg_match($timePattern, $this->averageLastOutTime) && preg_match($timePattern, $this->avergageFirstInTime)) {

            //         $lastOutTime = Carbon::createFromFormat('H:i:s', $this->averageLastOutTime);
            //         $firstInTime = Carbon::createFromFormat('H:i:s', $this->avergageFirstInTime);
                    
            //         // Calculate time difference if both times are valid
            //         $timeDifferenceFormatted = gmdate('H:i', $lastOutTime->diffInSeconds($firstInTime));
            //         $this->averageWorkHoursForModalTitle = $timeDifferenceFormatted;

            //     } else {
            //         // Log the issue for debugging purposes
            //         Log::warning('Invalid time format for averageLastOutTime or avergageFirstInTime.');
                    
            //         // Set fallback value
            //         $this->averageWorkHoursForModalTitle = '00:00'; 
            //     }
            
            $FirstInTimes = SwipeRecord::where('emp_id', $this->selectedEmployeeId)
                ->where('in_or_out', 'IN')
                ->whereBetween('created_at', [$formattedFromDate, $formattedToDate])
                ->select('swipe_time')->get();

            $FirstOutTimes = SwipeRecord::where('emp_id', $this->selectedEmployeeId)
                ->where('in_or_out', 'OUT')
                ->whereBetween('created_at', [$formattedFromDate, $formattedToDate])
                ->select('swipe_time')->get();

            $totalDuration = CarbonInterval::seconds(0);
            $totalDuration1 = CarbonInterval::seconds(0);

            foreach ($FirstInTimes as $record) {
                $time = Carbon::parse($record->swipe_time);
                $totalDuration->addSeconds($time->secondsSinceMidnight());
            }

            foreach ($FirstOutTimes as $record) {
                $time1 = Carbon::parse($record->swipe_time);
                $totalDuration1->addSeconds($time1->secondsSinceMidnight());
            }

            $this->totalDurationFormatted = $totalDuration->cascade()->format('%H:%I:%S');
            $this->totalDurationFormatted1 = $totalDuration1->cascade()->format('%H:%I:%S');

            // Convert total duration to seconds for calculation
            $totalSeconds = $totalDuration->totalSeconds;
            $totalSeconds1 = $totalDuration1->totalSeconds;

            $FirstInTimesCount = SwipeRecord::where('emp_id', $this->selectedEmployeeId)
                ->where('in_or_out', 'IN')
                ->whereBetween('created_at', [$formattedFromDate, $formattedToDate])
                ->count();

            if ($FirstInTimesCount > 0) {
                $this->avgDurationFormatted = $totalSeconds / $FirstInTimesCount;
            } else {
                $this->avgDurationFormatted = 0; // Handle division by zero
            }
        } catch (\Exception $e) {
            Log::error('Error in updateModalTitle method: ' . $e->getMessage());
            FlashMessageHelper::flashError('An error occurred while updating the modal title. Please try again later.');
            
        }
    }
    public function opentoggleButton()
    {

        $this->toggleButton = !$this->toggleButton;
    }
    private function calculateTotalWorkingDays($startDate,$endDate)
    {
        $workingDays = 0;
     
        // Iterate through the date range
        while ($startDate->lt($endDate)) {
            // Check if the day is not Saturday (6) or Sunday (7)
            if (!$startDate->isWeekend()) {
                $workingDays++;
            }
            // Move to the next day
            $startDate->addDay();
        }

        return $workingDays;
    }
    public function calculateTotalDays()
    {
        try {
            $employeeId = $this->selectedEmployeeId;
            $startDate = Carbon::parse($this->start_date_for_insights);
            $endDate = Carbon::parse($this->to_date);
            $originalEndDate = $endDate->copy();

            $this->updateModalTitle();
        } catch (\Exception $e) {
            Log::error('Error in calculateTotalDays method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while calculating total days. Please try again later.');
        }
    }
    private function calculateNumberofWeekends($startDate, $endDate)
    {
        $weekendDays = 0;
        $currentDate = $startDate->copy();
        while ($currentDate->lt($endDate)) {
            if ($currentDate->isSaturday() || $currentDate->isSunday()) {
                $weekendDays++;
            }
            $currentDate->addDay();
        }

        return $weekendDays;
    }
    private function calculateWorkingDays($startDate, $endDate, $employeeId)
    {
        try {

            $workingDays = 0;
            $currentDate = $startDate->copy();

            while ($currentDate->lte($endDate)) {
                if ($currentDate->isWeekday() && !$this->isEmployeeLeaveOnDate($currentDate, $employeeId) && $this->isEmployeePresentOnDate($currentDate)) {
                    $workingDays++;
                }
                $currentDate->addDay();
            }

            return $workingDays;
        } catch (\Exception $e) {
            Log::error('Error in calculateWorkingDays method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while calculating working days. Please try again later.');
            return 0;
        }
    }
    private function calculateWorkingDaysForModalTitle($startDate, $endDate, $employeeId)
    {
        try {

            $workingDays = 0;
            $currentDate = $startDate->copy();

            while ($currentDate->lt($endDate)) {
                if ($currentDate->isWeekday() && !$this->isEmployeeLeaveOnDate($currentDate, $employeeId) && $this->isEmployeePresentOnDate($currentDate)) {
                    $workingDays++;
                }
                $currentDate->addDay();
            }

            return $workingDays;
        } catch (\Exception $e) {
            Log::error('Error in calculateWorkingDays method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while calculating working days. Please try again later.');
            return 0;
        }
    }

    private function calculateActualHours($swipe_records)
    {
        try {
            $this->actualHours = [];

            for ($i = 0; $i < count($swipe_records) - 1; $i += 2) {
                $firstSwipeTime = strtotime($swipe_records[$i]->swipe_time);
                $secondSwipeTime = strtotime($swipe_records[$i + 1]->swipe_time);

                $timeDifference = $secondSwipeTime - $firstSwipeTime;

                $hours = floor($timeDifference / 3600);
                $minutes = floor(($timeDifference % 3600) / 60);

                $this->actualHours[] = sprintf("%02dhrs %02dmins", $hours, $minutes);
            }
        } catch (\Exception $e) {
            Log::error('Error in calculateActualHours method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while calculating actual hours. Please try again later.');
        }
    }
    public function viewDetails($id)
    {
        try {
            $this->showSR = true;
            $student = SwipeRecord::find($id);
            $this->view_student_emp_id = $student->emp_id;
            $this->view_student_first_name=EmployeeDetails::where('emp_id',$this->view_student_emp_id)->value('first_name');
            $this->view_student_last_name=EmployeeDetails::where('emp_id',$this->view_student_emp_id)->value('last_name');
            $this->view_student_swipe_time = $student->swipe_time;
            $this->view_student_in_or_out = $student->in_or_out;
        } catch (\Exception $e) {
            Log::error('Error in viewDetails method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while viewing details. Please try again later.');
        }
    }
    public function closeViewStudentModal()
    {
        try {
            $this->view_student_emp_id = '';
            $this->view_student_swipe_time = '';
            $this->view_student_in_or_out = '';
        } catch (\Exception $e) {
            Log::error('Error in closeViewStudentModal method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while closing view student modal. Please try again later.');
        }
    }
    public $show = false;
    public $show1 = false;
    public function showViewStudentModal()
    {
        try {
            $this->show = true;
        } catch (\Exception $e) {
            Log::error('Error in showViewStudentModal method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while showing view student modal. Please try again later.');
        }
    }

    public function showViewTableModal()
    {
        try {
            $this->show1 = true;
        } catch (\Exception $e) {
            Log::error('Error in showViewTableModal method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while showing view table modal. Please try again later.');
        }
    }

    public $showSR = false;
    public function openSwipes()
    {
        try {
            
            $this->showSR = true;
        } catch (\Exception $e) {
            Log::error('Error in openSwipes method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while opening swipes. Please try again later.');
        }
    }
    public function closeSWIPESR()
    {
        try {
            $this->view_student_emp_id = '';
            $this->view_student_swipe_time = '';
            $this->view_student_in_or_out = '';
            $this->showSR = false;
        } catch (\Exception $e) {
            Log::error('Error in closeSWIPESR method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while closing SWIPESR. Please try again later.');
        }
    }
    public function close1()
    {
        try {
            $this->show1 = false;
        } catch (\Exception $e) {
            Log::error('Error in close1 method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while closing 1. Please try again later.');
        }
    }
    public function calculateAvgWorkHoursForPreviousMonth()
    {
        // Get the start and end dates of the previous month
        $startDate = Carbon::now()->subMonth()->startOfMonth();
        $endDate = Carbon::now()->subMonth()->endOfMonth();

        // Retrieve all SwipeRecord entries for the previous month
        $records = SwipeRecord::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('swipe_time') // Ensure we order records by swipe time
            ->get();

        // Initialize variables
        $totalHours = 0;
        $recordCount = 0;

        // Group records by date
        $groupedRecords = $records->groupBy(function ($record) {
            return Carbon::parse($record->swipe_time)->toDateString();
        });

        // Iterate through each group (each day)
        foreach ($groupedRecords as $date => $dayRecords) {
            $swipeIn = $dayRecords->where('in_or_out', 'IN')->first();
            $swipeOut = $dayRecords->where('in_or_out', 'OUT')->last();

            if ($swipeIn && $swipeOut) {
                $swipeInTime = Carbon::parse($swipeIn->swipe_time);
                $swipeOutTime = Carbon::parse($swipeOut->swipe_time);

                // Calculate the difference in hours and add to total hours
                $totalHours += $swipeOutTime->diffInHours($swipeInTime);
                $recordCount++;
            }
        }

        // Calculate average hours worked
        $avgWorkHours = $recordCount > 0 ? $totalHours / $recordCount : 0;

        return $avgWorkHours;
    }
    public function calculateAvgWorkHours()
    {
        // Get the start and end dates of the current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Retrieve all SwipeRecord entries for the current month
        $records = SwipeRecord::whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();

        // Initialize total hours
        $totalHours = 0;
        $recordCount = 0;

        // Iterate through records and calculate total working hours
        foreach ($records as $record) {
            $swipeIn = Carbon::parse($record->swipe_in);
            $swipeOut = Carbon::parse($record->swipe_out);

            // Calculate the difference in hours and add to total hours
            $totalHours += $swipeOut->diffInHours($swipeIn);
            $recordCount++;
        }

        // Calculate average hours worked
        $avgWorkHours = $recordCount > 0 ? $totalHours / $recordCount : 0;

        return $avgWorkHours;
    }
    public function test()
    {
        Log::info('Checking employee presence for the following data:', [
            'employeeId' => $this->selectedEmployeeId,
            
        ]);
    }
    public function beforeMonth()
    {
        try {
            $date = Carbon::create($this->year, $this->month, 1)->subMonth();
            $this->year = $date->year;
            $this->month = $date->month;
            $today = Carbon::today();
            $this->generateCalendar();
            $startDateOfPreviousMonth = $date->startOfMonth()->toDateString();
            $endDateOfPreviousMonth = $date->endOfMonth()->toDateString();
            if ($today->year == $date->year && $today->month == $date->month && $endDateOfPreviousMonth > $today->toDateString()) {
                // Adjust $endDateOfPreviousMonth to today's date since it's greater than today

                $this->averageWorkHrsForCurrentMonth = $this->calculateAverageWorkHoursAndPercentage($startDateOfPreviousMonth, $today->toDateString());
            } elseif ($today->year >= $date->year && $today->month >= $date->month && $endDateOfPreviousMonth > $today->toDateString()) {
                $this->averageWorkHrsForCurrentMonth = '-';
            } else {
                $this->averageWorkHrsForCurrentMonth = $this->calculateAverageWorkHoursAndPercentage($startDateOfPreviousMonth, $endDateOfPreviousMonth);
            }
            //$this->averageWorkHrsForCurrentMonth = $this->calculateAverageWorkHoursAndPercentage($startDateOfPreviousMonth, $endDateOfPreviousMonth);


            // $previousMonthStart = $date->subMonth()->startOfMonth()->toDateString();
           
            $this->percentageinworkhrsforattendance=$this->calculateDifferenceInAvgWorkHours($date->format('Y-m'));
            
            $this->dateClicked($date->startOfMonth()->toDateString());
        } catch (\Exception $e) {
            Log::error('Error in beforeMonth method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while navigating to the previous month. Please try again later.');
        }
    }

    public function nextMonth()
    {
        try {
            $date = Carbon::create($this->year, $this->month, 1)->addMonth();
            $this->year = $date->year;
            $this->month = $date->month;
            $today = Carbon::today();
            $this->generateCalendar();
            $this->changeDate = 1;
            $this->dateClicked($date->toDateString());
            $nextdate = Carbon::create($date->year, $date->month, 1)->addMonth();
            $lastDateOfNextMonth = $date->endOfMonth()->toDateString();
            $startDateOfPreviousMonth = $date->startOfMonth()->toDateString();
            $endDateOfPreviousMonth = $date->endOfMonth()->toDateString();
            if ($today->year == $date->year && $today->month == $date->month && $endDateOfPreviousMonth > $today->toDateString()) {
                // Adjust $endDateOfPreviousMonth to today's date since it's greater than today

                $this->averageWorkHrsForCurrentMonth = $this->calculateAverageWorkHoursAndPercentage($startDateOfPreviousMonth, $today->toDateString());
            } elseif ($today->year >= $date->year && $today->month >= $date->month && $endDateOfPreviousMonth > $today->toDateString()) {
                $this->averageWorkHrsForCurrentMonth = '-';
            } else {
                $this->averageWorkHrsForCurrentMonth = $this->calculateAverageWorkHoursAndPercentage($startDateOfPreviousMonth, $endDateOfPreviousMonth);
            }
            $this->percentageinworkhrsforattendance=$this->calculateDifferenceInAvgWorkHours($date->format('Y-m'));
        } catch (\Exception $e) {
            Log::error('Error in nextMonth method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while navigating to the next month. Please try again later.');
        }
    }

    public function öpenattendanceperiodModal()
    {

        $this->öpenattendanceperiod = true;
    }
    public function closeattendanceperiodModal()
    {
        $this->öpenattendanceperiod = false;
        $this->start_date_for_insights = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->to_date = Carbon::now()->toDateString();
        $this->updateModalTitle();
    }
    public function checkDateInRegularisationEntries($d)
    {
        try {
            $this->showRegularisationDialog = true;
            $employeeId = $this->selectedEmployeeId;
            $regularisationRecords = RegularisationDates::where('emp_id', $employeeId)
                ->where('status', 2)
                ->get();
            $dateFound = false;
            $result = null;

            foreach ($regularisationRecords as $record) {
                $entries = json_decode($record->regularisation_entries, true);

                foreach ($entries as $entry) {
                    if (isset($entry['date']) && $entry['date'] === $d) {
                        $dateFound = true;
                        $result = [
                            'date' => $entry['date'],
                            'reason' => $entry['reason'],
                            'approved_date' => $record['approved_date'],
                            'approved_by' => $record['approved_by']
                        ];
                        break 2; // Exit both loops
                    }
                }
            }

            if ($result) {
                $this->regularised_date_to_check = $result['date'];
                $this->regularised_by = $result['approved_by'];
                $this->regularised_date = $result['approved_date'];
                $this->regularised_reason = $result['reason'];
            } else {
                $this->regularised_date_to_check = null;
                $this->regularised_by = null;
                $this->regularised_date = null;
                $this->regularised_reason = null;
            }
        } catch (\Exception $e) {
            // Handle the exception
            Log::error('Error in checkDateInRegularisationEntries method: ' . $e->getMessage());
            // Optionally, you can set a user-friendly message to be displayed
            $this->errorMessage = 'An error occurred while checking the regularisation entries. Please try again later.';

            // Reset the fields in case of an error
            $this->regularised_date_to_check = null;
            $this->regularised_by = null;
            $this->regularised_date = null;
            $this->regularised_reason = null;
        }
    }
   
  
    public function searchforEmployee()
    {
          $this->searchEmployee=1;
       
    }
  
    public function updateselectedEmployee($empId)
    {
    
        $this->selectedEmployeeId=$empId;
        $this->selectedEmployeeFirstName= EmployeeDetails::where('emp_id',$empId)->value('first_name');
        $this->selectedEmployeeLastName= EmployeeDetails::where('emp_id',$empId)->value('last_name');
        $this->searchEmployee=0;
    }
    public function closeEmployeeBox()
    {
        $this->searchEmployee=0;
       
       
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
        $this->employees = $this->getEmployeesByType();
        $this->generateCalendar();
   
        $this->dynamicDate = now()->format('Y-m-d');
        $employeeId = $this->selectedEmployeeId;
        $this->employeeIdForRegularisation = $this->selectedEmployeeId;
        $this->swiperecord = SwipeRecord::where('swipe_records.emp_id', $employeeId)
            ->where('is_regularized', 1)
            ->join('employee_details', 'swipe_records.emp_id', '=', 'employee_details.emp_id')
            ->select('swipe_records.*', 'employee_details.first_name', 'employee_details.last_name')
            ->get();

        $currentDate = Carbon::now()->format('Y-m-d');
        $holiday = HolidayCalendar::all();
        $today = Carbon::today();
        $data = SwipeRecord::join('employee_details', 'swipe_records.emp_id', '=', 'employee_details.emp_id')
            ->where('swipe_records.emp_id', $this->selectedEmployeeId)
            ->whereDate('swipe_records.created_at', $today)
            ->select('swipe_records.*', 'employee_details.first_name', 'employee_details.last_name')
            ->get();

        $this->holiday = HolidayCalendar::all();
        $this->leaveApplies = LeaveRequest::where('emp_id', $this->selectedEmployeeId)->get();

        if ($this->changeDate == 1) {
            $this->currentDate2 = $this->dateclicked;

            $this->currentDate2record = SwipeRecord::where('emp_id', $this->selectedEmployeeId)->whereDate('created_at', $this->currentDate2)->get();

            if (!empty($this->currentDate2record) && isset($this->currentDate2record[0]) && isset($this->currentDate2record[1])) {
                $this->first_in_time = substr($this->currentDate2record[0]['swipe_time'], 0, 5);
                $this->last_out_time = substr($this->currentDate2record[1]['swipe_time'], 0, 5);
                $firstInTime = Carbon::createFromFormat('H:i', $this->first_in_time);
                $lastOutTime = Carbon::createFromFormat('H:i', $this->last_out_time);

                if ($lastOutTime < $firstInTime) {
                    $lastOutTime->addDay();
                }

                if ($lastOutTime < $firstInTime) {
                    $lastOutTime->addDay();
                }

                $timeDifferenceInMinutes = $lastOutTime->diffInMinutes($firstInTime);
                $this->hours = floor($timeDifferenceInMinutes / 60);
                $minutes = $timeDifferenceInMinutes % 60;
                $this->minutesFormatted = str_pad($minutes, 2, '0', STR_PAD_LEFT);
            } elseif (!isset($this->currentDate2record[1]) && isset($this->currentDate2record[0])) {
                $this->first_in_time = substr($this->currentDate2record[0]['swipe_time'], 0, 5);
                $this->last_out_time = substr($this->currentDate2record[0]['swipe_time'], 0, 5);
            } else {
                $this->first_in_time = '-';
                $this->last_out_time = '-';
            }
            if ($this->first_in_time == $this->last_out_time) {
                $this->shortFallHrs = '08:59';
                $this->work_hrs_in_shift_time = '-';
            } else {
                $this->shortFallHrs = '-';
                $this->work_hrs_in_shift_time = '09:00';
            }
            $this->currentDate2recordexists = SwipeRecord::where('emp_id', $this->selectedEmployeeId)->whereDate('created_at', $this->currentDate2)->exists();
        } else {
            $this->currentDate2 = Carbon::now()->format('Y-m-d');
        }

        $swipe_records = SwipeRecord::where('emp_id', $this->selectedEmployeeId)->whereDate('created_at', $this->currentDate2)->get();
        $swipe_records_count = SwipeRecord::where('emp_id', $this->selectedEmployeeId)->whereDate('created_at', $this->currentDate2)->count();
        $this->swiperecordsfortoggleButton = SwipeRecord::where('emp_id', $this->selectedEmployeeId)->whereDate('created_at', $this->currentDate2)->get();
        $swipe_records1 = SwipeRecord::where('emp_id', $this->selectedEmployeeId)->orderBy('created_at', 'desc')->get();
       
        $this->calculateActualHours($swipe_records);
       
        return view('livewire.attendance-info-for-hr-display',[
            'Holiday' => $this->holiday,
            'Swiperecords' => $swipe_records,
            'SwiperecordsCount' => $swipe_records_count,
            'Swiperecords1' => $swipe_records1,
            'data' => $data,
            'CurrentDateTwoRecord' => $this->currentDate2record,
            'ChangeDate' => $this->changeDate,
            'CurrentDate2recordexists' => $this->currentDate2recordexists,
            'avgLateIn' => $this->avgLateIn,
            'avgEarlyOut' => $this->avgEarlyOut,
            'avgSignOutTime' => $this->avgSwipeOutTime,
            'modalTitle' => $this->modalTitle,
            'totalDays' => $this->totalDays
        ]);
    }
}
