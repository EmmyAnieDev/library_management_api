##  Introduce API Version 2 for Books
 
-   Added `BookV2` model for API version 2 under `App\Models\v2`.

-   Created a new `books_v2` table with updated structure:

-   Fields updated:
-   `name` → `title`
-   `owner` → `author`
-   `about` → `description`
-   `category_id` field remains unchanged and continues to reference the `categories` table.

##  Updated relationships:

-   `Book` v2 still references the same `Category` model from version 1.

-   Added API routes for version 2 (`/api/v2/books`) in `routes/api.php`.

-   Created new controller `App\Http\Controllers\Api\v2\BookV2Controller` for handling version 2 book-related logic.

-   Ensured backward compatibility with version 1 API (`/api/v1/books`) for existing users.
