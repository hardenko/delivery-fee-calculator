# Delivery Fee Calculator API

A Laravel-based service for calculating delivery fees based on destination, weight, and delivery type.

## Project Overview

This API calculates delivery fees for an e-commerce platform based on:
- Delivery type (standard or express)
- Package weight
- Destination (with special discount for Kyiv)

## Requirements

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Git](https://git-scm.com/downloads)

## Installation

```bash
git clone https://github.com/hardenko/delivery-fee-calculator.git .
chmod +x init.sh
./init.sh
```

## Usage

### API Endpoint

```
POST /api/calculate-delivery-fee
```

### Request Format

```json
{
  "destination": "kyiv",
  "weight": 3.5,
  "delivery_type": "express"
}
```

### Response Format

```json
{
  "fee": 104
}
```

### Business Rules

- Base fee for standard delivery: 50 UAH
- Base fee for express delivery: 100 UAH
- Additional fee: 10 UAH per kg for weight > 2kg
- 10% discount for deliveries to Kyiv

## Testing

Run tests with:

```bash
./vendor/bin/sail artisan test
```

The test suite includes:
- Feature test for the API endpoint
- Unit tests for fee calculation logic

## Architecture

The project follows SOLID principles:
- Service classes for fee calculation
- Dependency injection using Laravel's service container
- FormRequest for validation

## Stopping the Application

To stop the Docker containers:
```bash
./vendor/bin/sail down
```
