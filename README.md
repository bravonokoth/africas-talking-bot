# Africa's Talking SMS Lead Generation Bot

A Laravel 9 application that implements an intelligent SMS-based lead generation bot using the Africa's Talking SMS API in Sandbox environment. The bot facilitates customer engagement by collecting lead information through SMS conversations and storing it for follow-up.

> **Note:** Originally designed for WhatsApp, this project pivoted to SMS due to the Africa's Talking WhatsApp Sandbox endpoint being marked "Coming soon" as of February 26, 2025.

## Features

- 🤖 **Conversational Bot**: Responds to "lead" keyword with intelligent prompts
- 💾 **Data Capture**: Collects and validates name, email, and phone number from SMS messages
- 📨 **Automated Responses**: Sends confirmation and guidance messages via SMS
- 📊 **Lead Storage**: Stores captured leads in MySQL database for CRM integration
- 🔍 **Message Logging**: Comprehensive logging of incoming and outgoing SMS for debugging
- 🔐 **Sanctum Authentication**: API protection with Laravel Sanctum tokens

## Prerequisites

- **PHP** 8.0.2 or higher
- **Composer** - Dependency management
- **Laravel** 9.x framework
- **MySQL** - Or any Laravel-supported database
- **Africa's Talking Account** - Sandbox access with API key
- **Ngrok** - For exposing local server to receive webhooks
- **Node.js & npm** - For frontend assets (optional)

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/bravonokoth/africas-talking-bot.git
cd africas-talking-bot
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies (optional)
npm install
```

### 3. Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Setup Database

Edit `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chatbot
DB_USERNAME=root
DB_PASSWORD=your_password
```

Run migrations:

```bash
php artisan migrate
```

### 5. Configure Africa's Talking

Add your Africa's Talking credentials to `.env`:

```env
AFRICAS_TALKING_USERNAME=your_sandbox_username
AFRICAS_TALKING_API_KEY=your_api_key
AFRICAS_TALKING_SENDER_ID=your_sender_id
```

Or update `config/services.php`:

```php
'africastalking' => [
    'username' => env('AFRICAS_TALKING_USERNAME'),
    'api_key' => env('AFRICAS_TALKING_API_KEY'),
    'sender_id' => env('AFRICAS_TALKING_SENDER_ID'),
],
```

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── ChatController.php          # Webhook handler for SMS messages
│   └── Kernel.php
├── Models/
│   ├── Lead.php                        # Lead data model
│   └── User.php
├── Services/
│   └── AfricasTalkingService.php       # SMS API integration service
└── Providers/
    └── RouteServiceProvider.php

database/
├── migrations/
│   └── *_create_leads_table.php        # Leads table schema
└── seeders/

routes/
├── api.php                             # API endpoints
└── web.php

config/
├── services.php                        # Service credentials
└── ...
```

## API Endpoints

### Send SMS Webhook

**Endpoint:** `POST /api/webhook`

Receives incoming SMS messages from Africa's Talking and processes them.

**Request Body:**
```json
{
  "from": "+254712345678",
  "text": "lead",
  "date": "2025-02-26 10:30:00",
  "id": "message_id"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Webhook processed"
}
```

## Usage

### User Flow

1. **Initiate Lead Capture**
   - User sends SMS: `lead` to shortcode
   - Bot responds with prompt for user details

2. **Provide Information**
   - User sends formatted SMS:
   ```
   name: John Doe, email: john@example.com, phone: +254712345678
   ```

3. **Lead Confirmation**
   - Bot validates the data
   - Stores lead in database
   - Sends confirmation SMS

### Message Format

Users should send lead information in this format:

```
name: John Doe, email: john@example.com, phone: +254712345678
```

The bot uses regex patterns to extract:
- **Name**: Alphanumeric text after "name:"
- **Email**: Valid email format after "email:"
- **Phone**: Phone number starting with "+" after "phone:"

## Database Schema

### Leads Table

```sql
CREATE TABLE leads (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255),
  email VARCHAR(255),
  phone VARCHAR(255),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

## Configuration

### Enable Webhook in Africa's Talking

1. Log in to Africa's Talking Dashboard (Sandbox)
2. Navigate to **Messaging** → **SMS**
3. Set webhook URL to: `http://your-domain.com/api/webhook` (or use Ngrok)
4. Enable **Delivery Reports** for tracking

### Local Development with Ngrok

```bash
# Expose local server
ngrok http 8000

# Use the provided URL for webhook configuration
# Example: https://xxxx-xx-xxx-xxx.ngrok.io/api/webhook
```

## Development

### Run Local Server

```bash
php artisan serve
```

Server runs at `http://localhost:8000`

### Build Frontend Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### Run Tests

```bash
php artisan test
```

## Logging

All SMS interactions are logged to `storage/logs/laravel.log`. Check logs for debugging:

```bash
tail -f storage/logs/laravel.log
```

Log entries include:
- Incoming SMS webhook data
- Lead capture results
- SMS sending responses

## Error Handling

The bot handles various error scenarios:

- **Invalid Format**: Guides user on correct message format
- **Missing Fields**: Requests missing information
- **Invalid Email**: Validates email format
- **Invalid Phone**: Ensures phone number format with country code

## Technologies Used

- **Laravel 9.x** - PHP Framework
- **Sanctum** - API Authentication
- **Africa's Talking SDK** - SMS API Integration
- **MySQL** - Database
- **Vite** - Frontend build tool
- **Composer** - Dependency Management

## Performance

The application is optimized for:
- Fast webhook processing
- Efficient message parsing
- Minimal database queries
- Proper error logging

## Security

- ✅ API token validation via Sanctum
- ✅ Input validation and sanitization
- ✅ Secure credential storage in `.env`
- ✅ CORS protection
- ✅ Request logging and monitoring

## Troubleshooting

### Webhook Not Receiving Messages
- Ensure Ngrok URL is correctly configured in Africa's Talking Dashboard
- Check firewall/network settings
- Verify webhook endpoint is accessible

### Lead Not Saving
- Check database connection in `.env`
- Verify migrations have run: `php artisan migrate:status`
- Check message format matches regex patterns

### SMS Not Sending
- Verify Africa's Talking credentials in `.env`
- Check API key is valid in Sandbox environment
- Ensure sender ID is configured
- Check account balance in Africa's Talking Dashboard

### Empty Database
- Ensure database is created: `php artisan migrate`
- Verify correct database name in `.env`
- Check table exists: `php artisan tinker` → `Lead::all()`

## Future Enhancements

- [ ] WhatsApp integration when API becomes available
- [ ] Multi-language support
- [ ] Advanced lead scoring
- [ ] CRM integrations (HubSpot, Salesforce)
- [ ] Email notifications for new leads
- [ ] Admin dashboard for lead management
- [ ] Analytics and reporting

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request



## Support

For issues, questions, or suggestions:
- Open an issue on GitHub
- Contact: [Your Contact Info]
- Africa's Talking Support: https://africastalking.com/support

## Changelog

### v1.0.0 - February 26, 2025
- Initial release with SMS lead capture functionality
- Webhook integration with Africa's Talking
- Lead database storage and validation
