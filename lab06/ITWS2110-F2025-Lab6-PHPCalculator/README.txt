ANSWERS TO QUESTIONS:

EXPLANATION OF CLASSES, METHODS, AND EXECUTION FLOW:
    Classes and Methods:
    - Operation (abstract class): Base class that defines the structure for all operations
    - __construct($o1, $o2): Validates operands are numeric and stores them
    - operate() (abstract): Must be implemented by subclasses to perform calculation
    - getEquation() (abstract): Must be implemented to return formatted equation string

    - Addition, Subtraction, Multiplication, Division (concrete classes): Inherit from Operation
    - operate(): Performs the specific mathematical operation (+, -, *, /)
    - getEquation(): Returns formatted string like "5 + 3 = 8"

    Execution Flow After Button Click:
    1. User enters two numbers and clicks an operation button (e.g., "Add")
    2. Form submits POST request to lab6.php with op1, op2, and the button name/value
    3. PHP checks if REQUEST_METHOD == 'POST'
    4. Extracts $o1 and $o2 from $_POST array
    5. Checks which button was pressed using isset($_POST['add']), etc.
    6. Instantiates appropriate operation object (e.g., new Addition($o1, $o2))
    7. Constructor validates operands are numeric
    8. In the HTML section, checks if $op is set
    9. Calls $op->getEquation() which internally calls operate() to compute result
    10. Displays formatted equation in <pre id="result">

USING $_GET INSTEAD OF $_POST:
    If we used $_GET:
    - Values would appear in URL: lab6.php?op1=5&op2=3&add=Add
    - Data would be visible in browser history, bookmarks, and server logs
    - URLs could be shared/bookmarked with specific calculations
    - Less secure for sensitive data (though not relevant for a calculator)
    - URL length limits could be an issue with complex data
    - Not semantically correct - GET is for retrieving data, POST is for operations

    Why POST is preferable here:
    - POST is semantically correct for operations that change state or process data
    - Prevents accidental re-submission via URL refresh
    - Follows REST principles (POST for actions)
    - Cleaner URLs

    When GET might be acceptable:
    - If you wanted shareable calculator links
    - For simple read-only operations

ALTERNATIVE WAYS TO DETERMINE WHICH BUTTON WAS PRESSED:
    Better approach using a single operation selector:
    - Use a hidden input or select dropdown for operation type
    - Single submit button instead of four
    - Check value: if ($_POST['operation'] == 'add') { ... }

    Alternative: Use button values
    - <button name="operation" value="add">Add</button>
    - Single check: switch($_POST['operation']) { case 'add': ... }

    Even better: Use polymorphism more effectively
    - Create a factory pattern or array mapping
    - $operations = ['add' => 'Addition', 'sub' => 'Subtraction', ...]
    - Loop through to find which is set, instantiate dynamically
    - Reduces code duplication and makes adding operations easier

    Advantages:
    - Less repetitive if/else chains
    - Easier to maintain and extend
    - More scalable (easy to add new operations)
    - Cleaner code structure