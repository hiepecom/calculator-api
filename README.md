# REZOLVE CALCULATOR API EXTENSION

The extension creates an Magento2 API with a new endpoint that acts as a calculator.

The endpoint will accept POST data containing:
- two numbers
- the operator
-- add
-- subtract
-- multiply
-- divide
-- power
- Optionally, a precision parameter.

It will return an object with the result.
The extension is developed and tested based on Magento 2.1.11

## Endpoint
The endpoint is at /V1/api/rce/calculator, and accept a POST request.
For the purposes of this test, it is fine to allow anonymous access to the endpoint.

## Response Body 
The result should be a JSON object with the following structure

{
    "status": "OK",
    "result": 123.45
}

Or a standard Magento error response with a descriptive message of what the problem was.

## Installation
Upload the package to the folder app/code or install via composer.