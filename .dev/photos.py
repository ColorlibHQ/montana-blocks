"""Every photograph the theme ships: where it came from, and what it shows.

The HTML template's own photographs were traced by reverse image search and
none of them ship: they tell a tropical sea resort's story (overwater villas,
beach umbrellas, a Maldives pool deck), several could not be traced to a free
source at all, and Montana is a mountain lake hotel. These are Pexels
photographs chosen to fill the same slots -- the same crops, the same roles.

ALT was written from each picture at full size, never from its file name.
build_patterns.py reads ALT and FOCAL; readme.txt's credits are written from
SOURCES (python3 .dev/photos.py prints them).
"""

# slug: (Pexels id, photographer, photo page)
SOURCES = {
    "hero-1": ("1450208", "Marlon Martinez", "https://www.pexels.com/photo/body-of-water-near-house-1450208/"),
    "hero-2": ("33191386", "Alexandre Moreira", "https://www.pexels.com/photo/scenic-lake-cabin-in-italian-alpine-forest-33191386/"),
    "about-1": ("17821266", "Denner Trindade", "https://www.pexels.com/photo/a-wooden-house-in-mountains-17821266/"),
    "about-2": ("12003496", "Sarah O'Shea", "https://www.pexels.com/photo/grotto-library-at-palma-lobby-bar-in-santa-monica-proper-hotel-santa-monica-california-usa-12003496/"),
    "offer-1": ("6130068", "Quang Nguyen Vinh", "https://www.pexels.com/photo/modern-armchairs-on-terrace-with-fence-6130068/"),
    "offer-2": ("32767465", "Jean-Paul Wettstein", "https://www.pexels.com/photo/rustic-midsummer-bar-in-swiss-countryside-32767465/"),
    "offer-3": ("3087240", "Nuwan chamara", "https://www.pexels.com/photo/people-riding-boats-3087240/"),
    "video": ("27623535", "Donovan Kelly", "https://www.pexels.com/photo/grey-blue-27623535/"),
    "dining-1": ("28705621", "Filipp Romanovski", "https://www.pexels.com/photo/gourmet-fine-dining-plate-with-sauce-and-herbs-28705621/"),
    "dining-2": ("4640904", "ArtHouse Studio", "https://www.pexels.com/photo/man-and-woman-eating-dinner-on-patio-4640904/"),
    "room-1": ("5212392", "Luis Quintero", "https://www.pexels.com/photo/wooden-lounge-overlooking-calm-sea-5212392/"),
    "room-2": ("5784432", "Clay Elliot", "https://www.pexels.com/photo/white-and-red-floral-area-rug-5784432/"),
    "room-3": ("30070557", "Amar Preciado", "https://www.pexels.com/photo/cozy-mountain-chalet-with-scenic-view-30070557/"),
    "room-4": ("17399353", "Jonathan Borba", "https://www.pexels.com/photo/wide-bed-in-a-luxury-wooden-cabin-with-a-scenic-mountain-view-17399353/"),
    "gallery-1": ("6611017", "Chris", "https://www.pexels.com/photo/kayaking-on-a-lake-with-a-beautiful-view-6611017/"),
    "gallery-2": ("2437293", "eberhard grossgasteiger", "https://www.pexels.com/photo/boats-at-calm-body-of-water-by-mountain-slip-2437293/"),
    "gallery-3": ("19263508", "Mark A Jenkins", "https://www.pexels.com/photo/backpacker-in-mountains-19263508/"),
    "gallery-4": ("19737831", "Jonathan Borba", "https://www.pexels.com/photo/chairs-and-a-coffee-table-by-the-window-with-a-mountain-view-19737831/"),
    "gallery-5": ("12276515", "Fausto Hernández", "https://www.pexels.com/photo/brown-wooden-table-and-chairs-12276515/"),
    "wide-1": ("31665649", "Nadin Romanova", "https://www.pexels.com/photo/modern-hotel-terrace-with-mountain-view-in-georgia-31665649/"),
    "wide-2": ("38893778", "Jack Borno", "https://www.pexels.com/photo/charming-lodge-by-emerald-lake-in-british-columbia-38893778/"),
    "wide-3": ("3709821", "Heart Rules", "https://www.pexels.com/photo/restaurant-near-mountains-covered-with-snow-3709821/"),
    "banner-1": ("1619319", "James Wheeler", "https://www.pexels.com/photo/wooden-dock-at-the-lake-during-day-1619319/"),
    "banner-2": ("17164966", "Marko Mocilac", "https://www.pexels.com/photo/wooden-pier-at-the-shore-of-lake-bohinj-in-gorenjska-slovenia-17164966/"),
    "booking": ("29158145", "Amanda Brady", "https://www.pexels.com/photo/scenic-lakefront-log-cabin-in-colorado-mountains-29158145/"),
}

ALT = {
    "about-1": "A dark timber chalet with a long balcony, on a meadow of daisies below the hills",
    "about-2": "A hotel lounge with pale armchairs round a low table, bookshelves and framed pictures on timber walls",
    "offer-1": "Two round wicker armchairs on a covered balcony, looking over misty mountains",
    "offer-2": "A barrel sauna and a wooden hot tub beside an old timber hut in a green meadow below the forest",
    "offer-3": "Wooden rowing boats tied along a jetty on a green mountain lake, grey peaks above",
    "dining-1": "A plated main course with a golden fillet, glazed potatoes, mushrooms and a dark sauce",
    "dining-2": "A couple eating dinner at a candlelit wooden table outside, a snow-capped ridge behind them",
    "room-1": "A small table and two wooden chairs in a corner window over wide, still water and distant hills",
    "room-2": "A timber-framed sitting room with a stone fireplace, armchairs, a red rug and tall windows onto the hills",
    "room-3": "A wooden deck with high stools and a dining table under a timber roof, looking over forested hills",
    "room-4": "A wide bed facing the glass end wall of an A-frame cabin, hills beyond the balcony",
    "gallery-1": "The red bow of a kayak on a clear green lake, jagged snow-streaked mountains ahead",
    "gallery-2": "Two rowing boats on a still lake in the mist, the forested mountainside mirrored in the water",
    "gallery-3": "A walker in a yellow jacket and a sun hat crossing bare rock with a rucksack, pines behind",
    "gallery-4": "Two armchairs with sheepskins and a small round table in the sunlit glass end of an A-frame cabin",
    "gallery-5": "A table for two on a timber terrace under the trees, a green valley falling away below",
    "wide-1": "Wicker armchairs and potted pines on a long wooden terrace beside a hotel, clouds on the mountains",
    "wide-2": "A timber lodge on a stony point of a turquoise lake, framed by tall pines and a mountain",
    "wide-3": "Restaurant tables with checked cloths on a balcony, a jagged snowy peak and a range beyond",
}

# Cover photographs have no alt (they are backgrounds), but they are
# described here so the credits and the checks know what they hold.
DESCRIBED = {
    "hero-1": "A timber lodge beside a wooden bridge on a mountain lake, peaks and pines mirrored in the water",
    "hero-2": "A timber lodge on the shore of a bright green lake at the foot of a pine-covered mountain",
    "video": "A long wooden jetty running out into a dark lake between steep mountains",
    "banner-1": "A wooden jetty running out across a still lake towards forest and a snowy peak",
    "banner-2": "A weathered wooden pier on a lake shore, steep wooded mountains along the far side",
    "booking": "A log cabin on a rocky point of a calm lake, pines and a rounded hill beyond",
}

# Cover focal points, (x, y) from 0 to 1: the part of the picture that stays
# in view when a cover crops it.
FOCAL = {
    # The lodge sits right of centre; on a phone the crop keeps it, and the
    # centred title lands on the lake and the sky above the far peaks.
    "hero-1": (0.62, 0.5),
    "hero-2": (0.62, 0.55),
}


if __name__ == "__main__":
    for slug in sorted(SOURCES):
        pid, who, url = SOURCES[slug]
        print("%s.webp: %s, Pexels, %s" % (slug, who, url))
