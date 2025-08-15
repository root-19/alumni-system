# Chat System Documentation

## Overview
The chat system allows users to communicate with admin users in real-time. The system consists of two main components:

1. **UserChat** - For regular users to chat with admins
2. **AdminChat** - For admins to chat with users

## Features

### User Chat Features
- ✅ Real-time message updates (polls every 5 seconds)
- ✅ Admin selection (if multiple admins available)
- ✅ Message timestamps
- ✅ Online/offline status indicators
- ✅ Responsive design
- ✅ Message validation
- ✅ Loading states

### Admin Chat Features
- ✅ User list sidebar
- ✅ Real-time message updates (polls every 5 seconds)
- ✅ Message timestamps
- ✅ User selection
- ✅ Responsive design
- ✅ Message validation
- ✅ Loading states

## Database Structure

### Users Table
- `id` - Primary key
- `name` - User's full name
- `email` - User's email address
- `role` - User role ('admin', 'user', etc.)
- `profile_image_path` - Path to profile image (optional)

### Messages Table
- `id` - Primary key
- `sender_id` - Foreign key to users table (who sent the message)
- `receiver_id` - Foreign key to users table (who receives the message)
- `message` - The message content
- `created_at` - Timestamp when message was created
- `updated_at` - Timestamp when message was last updated

## How to Use

### For Users
1. Navigate to `/message` route
2. The system will automatically select an available admin
3. If multiple admins are available, you can switch between them using the dropdown
4. Type your message and press Enter or click Send
5. Messages will appear in real-time

### For Admins
1. Navigate to `/admin/report` or `/admin/giving-back` routes
2. Select a user from the sidebar
3. Type your message and press Enter or click Send
4. Messages will appear in real-time

## User Roles

### Admin Users
- Can chat with any regular user
- Can see all users in the sidebar
- Can send and receive messages

### Regular Users
- Can chat with admin users
- Cannot see other users
- Can send and receive messages

## Helper Methods

The User model includes several helper methods:

```php
// Check if user is admin
$user->isAdmin();

// Check if user is regular user
$user->isUser();

// Get all admin users
User::getAdmins();

// Get all regular users
User::getRegularUsers();
```

## Testing

To test the chat system:

1. **Create test users:**
   ```bash
   php artisan db:seed
   ```

2. **Login as different users:**
   - Admin: `admin@example.com` / `password`
   - User: `test@example.com` / `password`

3. **Access chat pages:**
   - User chat: `/message`
   - Admin chat: `/admin/report` or `/admin/giving-back`

## Troubleshooting

### No Admin Available
If no admin users are found, the system will display a "No Admin Available" message. To fix this:
1. Create an admin user in the database
2. Ensure the user has `role = 'admin'`

### No Users Available (Admin View)
If no regular users are found, the admin chat will show "No Users Available". To fix this:
1. Create regular users in the database
2. Ensure users have `role = 'user'` or no role set

### Messages Not Loading
If messages are not loading:
1. Check if the messages table exists
2. Verify foreign key relationships
3. Check if users exist in the database

## Security Features

- All routes are protected with authentication middleware
- Users can only see messages they're involved in
- Input validation prevents empty messages
- XSS protection through Laravel's built-in escaping

## Performance Considerations

- Messages are polled every 5 seconds for real-time updates
- Messages are ordered by creation time
- Pagination can be added for large message histories
- Database indexes should be added for sender_id and receiver_id columns

## Future Enhancements

- Real-time WebSocket support
- Message read receipts
- File/image sharing
- Message search functionality
- Message deletion
- User typing indicators
