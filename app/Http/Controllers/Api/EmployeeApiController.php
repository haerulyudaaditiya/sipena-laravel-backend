<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use App\Http\Controllers\Controller;

class EmployeeApiController extends Controller
{
    /**
     * Get the details of an employee by ID.
     */
    public function show($id)
    {
        // Find the employee by ID, including the associated user data
        $employee = Employee::with('user')->find($id);

        // If the employee doesn't exist, return a 404 response
        if (!$employee) {
            return response()->json([
                'message' => 'Employee not found'
            ], 404);
        }

        // Return employee data as a JSON response
        return response()->json([
            'employee' => $employee
        ], 200);
    }
}
