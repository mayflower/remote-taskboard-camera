#!/bin/bash
BASEDIR="/home/mayflower/remote-taskboard-camera"

# Frames per script-run.  0 ^= infinitely
FRAMES=1
# Interval between frames in seconds
INTERVAL=0

# Check for gphoto2 installation
command -v gphoto2 >/dev/null 2>&1 || { echo >&2 "gphoto2 is not installed.  Aborting..."; exit 1; }

# Check for download dir
if [ ! -d "$BASEDIR/downloaded-photos" ]; then
	echo "Creating folder \"$BASEDIR/downloaded-photos\"..."
	mkdir $BASEDIR/downloaded-photos
fi

BASEDIR=$BASEDIR gphoto2 --capture-image-and-download -F $FRAMES -I $INTERVAL --filename "$BASEDIR/downloaded-photos/%Y-%m-%d_%H-%M.jpg" --hook-script "$BASEDIR/process.hook"

exit 0
