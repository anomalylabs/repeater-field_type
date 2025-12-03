# Repeater Field Type

*anomaly.field_type.repeater*

A powerful field type for creating repeatable data structures with custom fields in PyroCMS.

## Description

The repeater field type allows you to create repeatable groups of fields, perfect for building flexible content structures like FAQ sections, feature lists, gallery items, or any repeating data pattern. Each repeater instance can contain multiple configured fields.

## Features

- **Custom Field Groups**: Define any combination of fields for each repeater item
- **Unlimited Repetitions**: Add as many items as needed (or set min/max limits)
- **Sortable Items**: Drag and drop to reorder repeater entries
- **Inline Editing**: Edit items directly within the form
- **Validation**: Full validation support for nested fields
- **Relationship Support**: Uses proper database relationships
- **Flexible Configuration**: Configure fields dynamically

## Configuration

### Basic Configuration

```php
'faq_items' => [
    'type'   => 'anomaly.field_type.repeater',
    'config' => [
        'related' => 'example.module.faq_items',
    ],
],
```

### Configuration Options

#### `related` (required)
The related stream for repeater entries.

```php
'config' => [
    'related' => 'example.module.features', // Stream slug
]
```

#### `min`
Minimum number of required items.

```php
'config' => [
    'related' => 'example.module.items',
    'min'     => 1, // At least 1 item required
]
```

#### `max`
Maximum number of allowed items.

```php
'config' => [
    'related' => 'example.module.items',
    'max'     => 10, // Maximum 10 items
]
```

#### `add_row`
Custom label for the "Add Item" button.

```php
'config' => [
    'related' => 'example.module.testimonials',
    'add_row' => 'Add Testimonial',
]
```

## Setting Up a Repeater

### Step 1: Create the Repeater Stream

First, create a stream for your repeater entries:

```php
protected $stream = [
    'slug'   => 'faq_items',
    'title_column' => 'question',
    'translatable' => false,
];

protected $fields = [
    'question' => 'anomaly.field_type.text',
    'answer'   => 'anomaly.field_type.textarea',
];

protected $assignments = [
    'question' => [
        'required' => true,
    ],
    'answer' => [
        'required' => true,
    ],
];
```

### Step 2: Add Repeater Field to Parent Stream

```php
protected $fields = [
    'title' => 'anomaly.field_type.text',
    'faq'   => [
        'type'   => 'anomaly.field_type.repeater',
        'config' => [
            'related' => 'example.module.faq_items',
            'add_row' => 'Add FAQ Item',
        ],
    ],
];
```

## Usage Examples

### FAQ Section

```php
// Migration for FAQ items stream
protected $stream = [
    'slug' => 'faq_items',
];

protected $fields = [
    'question' => 'anomaly.field_type.text',
    'answer'   => 'anomaly.field_type.wysiwyg',
];

// Main page stream
protected $fields = [
    'page_title' => 'anomaly.field_type.text',
    'faqs'       => [
        'type'   => 'anomaly.field_type.repeater',
        'config' => [
            'related' => 'example.module.faq_items',
            'min'     => 3,
            'max'     => 20,
        ],
    ],
];
```

### Feature List

```php
// Features stream
protected $stream = [
    'slug' => 'features',
];

protected $fields = [
    'icon'        => 'anomaly.field_type.text',
    'title'       => 'anomaly.field_type.text',
    'description' => 'anomaly.field_type.textarea',
    'link'        => 'anomaly.field_type.url',
];

// Product stream
'features' => [
    'type'   => 'anomaly.field_type.repeater',
    'config' => [
        'related' => 'products.module.features',
        'add_row' => 'Add Feature',
    ],
],
```

### Team Members

```php
// Team members stream
protected $stream = [
    'slug' => 'team_members',
];

protected $fields = [
    'photo'    => 'anomaly.field_type.file',
    'name'     => 'anomaly.field_type.text',
    'position' => 'anomaly.field_type.text',
    'bio'      => 'anomaly.field_type.textarea',
    'email'    => 'anomaly.field_type.email',
];

// About page
'team' => [
    'type'   => 'anomaly.field_type.repeater',
    'config' => [
        'related' => 'pages.module.team_members',
        'add_row' => 'Add Team Member',
    ],
],
```

### Image Gallery

```php
// Gallery items stream
protected $stream = [
    'slug' => 'gallery_items',
];

protected $fields = [
    'image'   => 'anomaly.field_type.file',
    'caption' => 'anomaly.field_type.text',
    'alt_text' => 'anomaly.field_type.text',
];

// Gallery repeater
'images' => [
    'type'   => 'anomaly.field_type.repeater',
    'config' => [
        'related' => 'gallery.module.gallery_items',
        'min'     => 1,
        'add_row' => 'Add Image',
    ],
],
```

## Accessing Values

### Basic Output

```php
// Get all repeater items
$items = $entry->faq; // Collection of items

// Loop through items
foreach ($entry->faq as $item) {
    echo $item->question;
    echo $item->answer;
}

// Count items
$count = $entry->faq->count();

// Check if has items
if ($entry->faq->isNotEmpty()) {
    // Has items
}
```

### In Templates

```twig
{# Loop through repeater items #}
{% if entry.faq|length %}
    <div class="faq-section">
        {% for item in entry.faq %}
            <div class="faq-item">
                <h3>{{ item.question }}</h3>
                <p>{{ item.answer }}</p>
            </div>
        {% endfor %}
    </div>
{% endif %}

{# Access specific item #}
{{ entry.faq.first().question }}

{# Count items #}
<p>{{ entry.faq|length }} FAQ items</p>
```

### Advanced Template Usage

```twig
{# Features with icons #}
<div class="features-grid">
    {% for feature in entry.features %}
        <div class="feature-card">
            <i class="{{ feature.icon }}"></i>
            <h4>{{ feature.title }}</h4>
            <p>{{ feature.description }}</p>
            {% if feature.link %}
                <a href="{{ feature.link }}">Learn More</a>
            {% endif %}
        </div>
    {% endfor %}
</div>
```

### Presenter Output

```php
// Access via presenter
$items = $entry->present()->faq;

// Get specific presenter methods
foreach ($entry->present()->faq as $item) {
    echo $item->question;
    echo $item->answer->parse(); // Parse markdown, etc.
}
```

## Setting Values Programmatically

### Creating Repeater Items

```php
use Anomaly\ExampleModule\Faq\FaqModel;
use Anomaly\ExampleModule\Faq\FaqItemModel;

// Create parent entry
$faq = FaqModel::create([
    'title' => 'Common Questions',
]);

// Create repeater items
$item1 = FaqItemModel::create([
    'question' => 'What is PyroCMS?',
    'answer'   => 'PyroCMS is a powerful content management system.',
]);

$item2 = FaqItemModel::create([
    'question' => 'How do I install it?',
    'answer'   => 'Use Composer to install PyroCMS.',
]);

// Associate items with parent
$faq->faq()->sync([$item1->id, $item2->id]);
```

### Updating Repeater Items

```php
// Get existing items
$items = $entry->faq;

// Update first item
$items->first()->update([
    'question' => 'Updated question',
]);

// Add new item
$newItem = FaqItemModel::create([
    'question' => 'New question',
    'answer'   => 'New answer',
]);

$entry->faq()->attach($newItem->id);
```

## Database Structure

The repeater field type creates a many-to-many relationship:

```php
// Pivot table is created automatically
// Example: {parent_table}_{field_name}
// Contains: {parent}_id, {related}_id, sort_order
```

## Validation

```php
'faq_items' => [
    'type'  => 'anomaly.field_type.repeater',
    'rules' => [
        'required',
        'array',
        'min:1', // At least 1 item
        'max:10', // Maximum 10 items
    ],
    'config' => [
        'related' => 'example.module.faq_items',
    ],
],
```

### Validating Nested Fields

Validation for nested fields is defined on the repeater stream:

```php
protected $assignments = [
    'question' => [
        'required' => true,
        'rules'    => ['min:10'],
    ],
    'answer' => [
        'required' => true,
        'rules'    => ['min:50'],
    ],
];
```

## Common Use Cases

### Content Blocks
```php
'content_sections' => [
    'type'   => 'repeater',
    'config' => [
        'related' => 'pages.module.content_blocks',
    ],
],
```

### Pricing Tables
```php
'pricing_features' => [
    'type'   => 'repeater',
    'config' => [
        'related' => 'products.module.features',
        'add_row' => 'Add Feature',
    ],
],
```

### Testimonials
```php
'testimonials' => [
    'type'   => 'repeater',
    'config' => [
        'related' => 'pages.module.testimonials',
        'min'     => 3,
        'max'     => 12,
    ],
],
```

### Contact Information
```php
'locations' => [
    'type'   => 'repeater',
    'config' => [
        'related' => 'company.module.locations',
        'add_row' => 'Add Location',
    ],
],
```

## Best Practices

### Stream Organization
- Use clear, descriptive stream slugs
- Keep repeater streams focused and simple
- Use translatable streams when needed
- Set appropriate title columns

### Performance
- Eager load repeater relationships
- Limit max items to prevent performance issues
- Use pagination for large repeater sets
- Index frequently queried fields

### User Experience
- Provide clear "Add Item" button labels
- Set reasonable min/max limits
- Use required validation thoughtfully
- Group related fields logically

## Advanced Usage

### Conditional Fields in Repeaters

```php
// In the repeater stream migration
protected $fields = [
    'type'    => [
        'type'   => 'anomaly.field_type.select',
        'config' => [
            'options' => [
                'text'  => 'Text Block',
                'image' => 'Image Block',
                'video' => 'Video Block',
            ],
        ],
    ],
    'text_content' => 'anomaly.field_type.wysiwyg',
    'image_file'   => 'anomaly.field_type.file',
    'video_url'    => 'anomaly.field_type.url',
];
```

### Nested Repeaters

While not directly supported, you can achieve nested structures:

```php
// Parent repeater items can have their own repeater fields
// pointing to child repeater streams
```

### Eager Loading

```php
// Load entries with repeater items
$entries = EntryModel::with('faq')->get();

// In templates, items are already loaded
foreach ($entries as $entry) {
    foreach ($entry->faq as $item) {
        // No additional queries
    }
}
```

## Troubleshooting

### Items Not Saving
- Ensure related stream exists
- Check field validation rules
- Verify pivot table was created
- Check for JavaScript errors

### Performance Issues
- Limit number of items with `max`
- Eager load relationships
- Add database indexes
- Optimize field types used

### Sorting Not Working
- Ensure JavaScript is loaded
- Check for conflicting scripts
- Verify sort_order column exists

## Requirements

- PyroCMS 3.x
- Anomaly Streams Platform ^1.10

## License

This field type is open-sourced software licensed under the [MIT license](LICENSE.md).

## Authors

- **PyroCMS, Inc.** - [Website](http://pyrocms.com/) - ryan@pyrocms.com
- **Ryan Thompson** - [Website](http://ryanthepyro.com/)
