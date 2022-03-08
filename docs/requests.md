#Requests

## Setup
Create a new Request extend `App\Shared\Infrastructure\Http\Request\AbstractRequest`.
Implement the constraint method using symfony constraints and custom constraints. Also see the symfony docs.

Implement the validationData method, make sure you are explicit in which data you want to use from the request.

To use the request you can import it into your controller. The validate method will be called automatically. It is 
configured using service method calls.

## Docs
### Constraints
https://symfony.com/doc/current/reference/constraints.html#basic-constraints
https://symfony.com/doc/current/validation/custom_constraint.html

### Service Method Calls
https://symfony.com/doc/current/service_container/calls.html