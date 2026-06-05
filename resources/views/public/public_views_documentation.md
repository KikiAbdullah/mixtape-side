# MixtapeSide Public Views Documentation

This document outlines the structure and content of the public-facing views in the MixtapeSide Laravel application. All public views extend from `layouts.header-public` and utilize a premium custom CSS theme (`public-theme-premium.css`).

## Menu Structure (from `layouts.navbar-public.blade.php`)

The public navigation bar provides the following main menu items:

*   **Home** (`/`): The landing page of the application.
*   **Bands** (`/bands`): Directory of all bands.
*   **Releases** (`/discovery`): Redirects to the Discovery page to search for releases.
*   **Gigs** (`/gigs`): Calendar and directory of upcoming and past events.
*   **Discovery** (`/discovery`): Global search engine for bands, releases, labels, and gigs.

Additionally, it includes:
*   Language selector (`ID | EN`)
*   Authentication actions (Login/Register buttons for guests, Dashboard link for authenticated users).

---

## Public Pages Details

### 1. Home Page (`home.blade.php`)
The main landing page designed with a premium, engaging aesthetic.

**Content:**
*   **Hero Section**:
    *   Dynamic background image.
    *   Catchy handwritten tagline: "Rock archive, live culture, local scene".
    *   Main title: "MIXTAPESIDE".
    *   Subtitle: "Your Gateway to Local Music Heritage".
    *   Global search bar (redirects to `/discovery`).
    *   Key statistics: "Latest Releases", "Upcoming Gigs", "Archive Updates".
*   **Latest Releases Section**:
    *   Displays a list of the most recent music releases.
    *   Each release card shows cover art, release type (badge), title, artist, and release year.
    *   Link to view all releases (redirects to `/discovery`).
*   **Upcoming Gigs Section (Sidebar)**:
    *   Lists upcoming events/gigs.
    *   Each gig item shows title, date, venue, city, and organizer.
    *   Link to view all gigs (`/gigs`).
*   **Archive Activity Section (Sidebar)**:
    *   Displays recent activities and updates from the archive logs.

---

### 2. Discovery Page (`discovery.blade.php`)
A global search engine for various content types within MixtapeSide.

**Content:**
*   **Search Hero Section**:
    *   Title: "Discovery Search".
    *   Description: "Find bands, releases, labels, and gigs in one place".
    *   Search input field with a search button.
    *   Displays search query and result count if a query is active.
*   **Results Tabs**:
    *   Categorized results for "Bands", "Releases", "Labels", and "Gigs".
    *   Each tab shows a count of matching items.
*   **Tab Content**:
    *   **Bands Tab**: Lists matching bands with name, city, and genre.
    *   **Releases Tab**: Lists matching releases with release type, title, artist, and release year.
    *   **Labels Tab**: Lists matching record labels with name, city, and formed year.
    *   **Gigs Tab**: Lists matching events with date, title, venue, and city.
*   **No Search Query State**: Displays a prompt to start searching if no query is active.

---

### 3. Bands Directory (`band/index.blade.php`)
A comprehensive directory for browsing and filtering bands.

**Content:**
*   **Page Hero Section**:
    *   Title: "Band Directory".
    *   Description: "Search bands by genre, city, status, and formed year".
*   **Filters Sidebar**:
    *   Input fields for filtering by "Genre", "City", "Band Status" (Active, On Hold, Split-up), and "Formed Year" range.
    *   "Apply Filters" and "Reset" buttons.
*   **Bands Listing**:
    *   Displays bands in a card-based grid layout.
    *   Each band card shows logo, name, city, country, genres (badges), and status with formed year.
    *   Pagination links at the bottom.

---

### 4. Band Profile Page (`band/show.blade.php`)
Detailed profile page for a specific band.

**Content:**
*   **Band Header Card**:
    *   Band logo/photo.
    *   Band name, primary genres.
    *   Origin city/country, active years, and current status (Active/Split-up).
    *   Social media links (if available).
    *   "Claim Band Profile" button for unowned profiles (visible to authenticated users).
*   **Biography & History Section**:
    *   Detailed biography of the band.
*   **Tabs Section**:
    *   **Diskografi (Releases) Tab**: Lists the band's releases, grouped by type (e.g., Album, EP, Single). Each release shows cover art, title, and year.
    *   **Personel (Members) Tab**: Lists current and past band members with their instruments and active years.
    *   **Riwayat Panggung (Gigs) Tab**: Shows upcoming and past gigs the band participated in, with gig title, date, venue, and city.

---

### 5. Gigs Calendar (`gig/index.blade.php`)
A calendar and directory for browsing events/gigs.

**Content:**
*   **Page Hero Section**:
    *   Title: "Gigs & Events Calendar".
    *   Description: "Explore show schedules and find nearby events".
*   **Filters Sidebar**:
    *   Input fields for filtering by "City" and "Start Date" range.
    *   "Search Gigs" and "Reset" buttons.
*   **Gigs Listing**:
    *   Displays gigs in a card-based layout (poster on one side, details on the other).
    *   Each gig card shows poster, date, title, venue, city, and organizer.
    *   "No Poster" placeholder if an image is not available.
    *   Pagination links at the bottom.

---

### 6. Gig Detail Page (`gig/show.blade.php`)
Detailed information about a specific gig/event.

**Content:**
*   **Gig Header Card**:
    *   Gig poster.
    *   Gig title.
    *   Date & Time, Ticket Price/Info, Location (venue name & address, city).
    *   Organizer details.
    *   Interaction buttons: "Attend" (for logged-in users), "Interested" (for logged-in users), or a login prompt.
*   **Line-up Bands Section**:
    *   Lists all bands scheduled to perform at the gig.
    *   Each band item shows logo, name, city, and a link to their profile.
*   **Label Partners & Tenants Section**:
    *   Lists record labels involved as partners or tenants, with their roles.

---

### 7. Release Detail Page (`release/show.blade.php`)
Detailed information about a specific music release.

**Content:**
*   **Release Header Card**:
    *   Cover art.
    *   Release type (badge), title, and artist (linked to band profile).
    *   Original release year, total track count.
    *   Release notes/description.
*   **Tracklist Table**:
    *   Lists all tracks with track number, title, and duration.
    *   Clicking a track opens a modal for lyrics and contributor credits.
*   **Track Modal (for each track)**:
    *   Displays track title.
    *   **Lyrics Section**: Shows lyrics and translation/footnotes (if available).
    *   **Credits & Contributors Section**: Lists musicians and engineers involved in the track, with their roles.
*   **Release Label / Press Info**:
    *   Details about the record label(s) involved (if not self-released).
    *   Information includes label name, format, press year, press type, catalog number, and notes.

---

### 8. Label Profile Page (`label/show.blade.php`)
Detailed profile page for a specific record label.

**Content:**
*   **Label Header Card**:
    *   Label logo.
    *   Label name, type (e.g., "Independent Record Label").
    *   Origin city, formed year, and current status.
    *   Website and contact email links (if available).
*   **About Label Section**:
    *   Description/history of the record label.
*   **Tabs Section**:
    *   **Catalog Rilisan (Catalog) Tab**: Lists all releases associated with the label. Each release shows cover art, type, title, artist, and format/year.
    *   **Roster Band (Roster) Tab**: Lists bands signed to or associated with the label. Each band shows logo, name, and city.
*   **Sidebar Info**:
    *   Quick facts: Country, City, Formed Year, Defunct Year (if applicable), Status.
