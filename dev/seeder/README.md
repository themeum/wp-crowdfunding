# Crowdfunding test-data seeder (development only)

The seeder fills a local site with crowdfunding test data. It creates campaigns in every situation the plugin handles, together with their pledges, users, rewards, bookmarks and reviews. You can then test each feature without building the data by hand.

The whole `dev/` folder is **excluded from the release build** by `'!./dev/**'` in the `copy` task of `gulpfile.js`. `wp-crowdfunding.php` loads the seeder only when `WPCF_ENV` is `'development'` and `dev/seeder/seeder.php` exists, so the shipped plugin never loads it.

## Use

1. Make sure WooCommerce is active.
2. Open **wp-admin → Tools → Crowdfunding Seeder**.
3. Click **Seed test data**. Clicking it again later resets the data and seeds it fresh.
4. Check the table on that page. For every campaign it shows:
   - the live raised amount;
   - the percentage;
   - the days left;
   - the expected and actual state, with a PASS or FAIL result.
5. Click **Remove seeded data** to delete everything the seeder created and to restore the options it changed.

The page only appears while `WPCF_ENV` is `'development'`. That is the default in the source tree, and the build (`npm run build`) sets it to `'production'` in the released copy.

To hide the page during development, add `define( 'WPCF_DEV_SEEDER', false );` to `wp-config.php`.

## What gets seeded

- **Users** (the password for all of them is `password`):
  - 3 campaign creators: one with a full profile, one with a minimal profile, and one with no campaigns;
  - 4 backers: one with many pledges, one with a single pledge, one who pledges anonymously, and one with bookmarks only.
- **Categories:** 3 categories marked as crowdfunding, 1 unmarked category, and 3 tags.
- **22 campaigns** covering every end method (`target_date`, `target_goal`, `target_goal_and_date`, `never_end`, or none) and these states:
  - running, upcoming, successful, expired or failed, ends today, over-funded;
  - post status draft or pending;
  - rewards that are sold out, partly claimed or unlimited, or whose delivery date has passed;
  - pledge limits (minimum, maximum, recommended and predefined amounts);
  - anonymous contributors;
  - rich content (video, gallery and updates), and a bare minimum campaign.
- **Pledges** are WooCommerce orders:
  - mostly `completed`, which is the only status that counts toward the raised amount;
  - one order in each of `processing`, `on-hold`, `pending`, `cancelled`, `refunded` and `failed`, all on campaign 02, to prove those don't count;
  - one guest order;
  - dates spread over the last 14 days, which gives the dashboard report chart data.
- **Bookmarks** (`loved_campaign_ids`) on two of the backers.
- **Reviews:** approved reviews with star ratings, plus one pending review.
- **Images:** generated locally with GD. Nothing is downloaded. If GD is missing, the campaigns are seeded without images.

## WooCommerce compatibility

The seeder works on WooCommerce versions before and after 7.1, with any order storage:
- legacy posts;
- HPOS;
- HPOS with compatibility sync.

It manages this as follows:
- **Orders** are created and deleted only through the WooCommerce CRUD API: `wc_create_order()`, `$order->update_meta_data()`, `$order->save()` and `$order->delete()`. It never writes to `wp_posts`, `wp_postmeta` or the `wc_orders` tables directly.
- **Every seeded ID is recorded** in the `_wpcf_seeder_registry` option. Removal works from that list and never needs a meta query, so it behaves the same in every storage mode. Each seeded object is also marked with `_wpcf_seeded` meta.
- **The environment box** at the top of the page shows the WooCommerce version and the active storage. It shows a warning when HPOS is on but the plugin's own `Functions::hpos_enabled()` reports it as off. That check only looks at the setting on WooCommerce 8.2 and later, and `get_total_fund()` relies on it. In that case the FAIL rows show which totals are wrong.

To test every storage mode, reseed after each change:
- **HPOS on or off:** WooCommerce → Settings → Advanced → Features. Try it with compatibility sync both on and off.
- **WooCommerce before 7.1:** install WooCommerce 7.0.x from the wordpress.org plugin archive.

## Files

| File | Purpose |
|---|---|
| `seeder.php` | Loader (admin only, honours `WPCF_DEV_SEEDER`) |
| `class-seeder.php` | `WPCF\Dev\Seeder`: `seed()` and `unseed()` |
| `class-admin-page.php` | The Tools page, its actions and the PASS/FAIL table |
