#!/bin/bash
BASEDIR="/home/mayflower/remote-taskboard-camera"
FILENAME=`date +"%Y-%m-%d_%H-%M.jpg"`

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

BASEDIR=$BASEDIR gphoto2 --capture-image-and-download -F $FRAMES -I $INTERVAL --filename "$BASEDIR/$FILENAME" --hook-script "$BASEDIR/gphoto2-scripts/process.hook"


# Process image in remote-server
REMOTE_SERVER="mayflower@172.21.0.45"
REMOTE_BASEDIR="/srv/remote-taskboard-camera"
REMOTE_DOWNLOAD_DIR="/srv/remote-taskboard-camera/downloaded-photos"
REMOTE_OUTPUT_DIR="/srv/gallery/Fotos/team-bahag-taskboard"

ssh $REMOTE_SERVER 'cd '"$REMOTE_BASEDIR"' && php ./post-process.php -i '"$REMOTE_DOWNLOAD_DIR"'/'"$FILENAME"' -o '"$REMOTE_OUTPUT_DIR"'/'"$FILENAME"
ssh $REMOTE_SERVER 'rm '"$REMOTE_DOWNLOAD_DIR"'/'"$FILENAME"

exit 0
