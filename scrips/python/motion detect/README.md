# Motion Detection with OpenCV

This project implements a motion detection system using OpenCV. The code captures video frames, processes them to detect motion, and saves the frames where motion is detected into an output video file. The implementation includes frame skipping to improve performance.

## Features

- **Motion Detection**: Detects motion by comparing consecutive frames.
- **Frame Skipping**: Skips a specified number of frames to reduce processing load and improve performance.
- **Recording Indicator**: Displays a recording indicator on the video frames when motion is detected.
- **Output Video**: Saves the frames with detected motion into an output video file.

## Requirements

- Python 3.x
- OpenCV
- NumPy

## Installation

You can install the required packages using pip:

```bash
pip install opencv-python numpy