<?php
include('mvc/CustomerModel.php');
class CustomerController
{
    private $customerModel;

    public function __construct(CustomerModel $customerModel)
    {
        $this->customerModel = $customerModel;
    }

    public function registerCustomer($firstName, $lastName, $email, $password, $address, $phoneNumber)
    {
        // Validate inputs
        $errors = [];

        if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($address) || empty($phoneNumber)) {
            $errors[] = "All fields are required.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email address.";
        }

        // You can add more validation rules as needed

        if (!empty($errors)) {
            // Display validation errors to the user
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
            return;
        }

        // Sanitize inputs
        $sanitizedFirstName = filter_var($firstName, FILTER_SANITIZE_STRING);
        $sanitizedLastName = filter_var($lastName, FILTER_SANITIZE_STRING);
        $sanitizedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        $sanitizedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password for storage
        $sanitizedAddress = filter_var($address, FILTER_SANITIZE_STRING);
        $sanitizedPhoneNumber = filter_var($phoneNumber, FILTER_SANITIZE_STRING);

        // Register the customer
        $result = $this->customerModel->registerCustomer(
            $sanitizedFirstName,
            $sanitizedLastName,
            $sanitizedEmail,
            $sanitizedPassword,
            $sanitizedAddress,
            $sanitizedPhoneNumber
        );

        // Handle the result (e.g., redirect to login page or show a success message)
        if ($result) {
            echo "Registration successful. You can now log in.";
            // Redirect or display a login link
        } else {
            echo "Registration failed. Please try again.";
            // Handle registration failure
        }
    }

    // ...
}
?>
