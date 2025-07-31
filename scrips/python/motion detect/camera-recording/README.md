# Motion Detection with Video Recording

This Python script implements a motion detection system using OpenCV. It captures video from a specified source, detects motion, and saves the recorded video in a structured folder format. The script also supports logging for monitoring and debugging purposes.

## Features

- **Motion Detection**: Detects motion based on frame differences and saves video when motion is detected.
- **Video Recording**: Records video in segments and saves them in hourly folders.
- **Logging**: Logs important events and errors to a log file and the console.
- **Multiprocessing**: Utilizes Python's multiprocessing to run the motion detection in a separate process.
- **Error Handling**: Implements robust error handling and recovery mechanisms.

## Code Overview

### Imports

The script imports necessary libraries:
- `cv2`: OpenCV for video processing.
- `os`: For file and directory operations.
- `numpy`: For numerical operations (not heavily used in this version).
- `time`: For time-related functions.
- `multiprocessing`: For running the motion detection in a separate process.
- `logging`: For logging events and errors.
- `datetime`: For handling date and time.
- `signal`: For handling termination signals.
- `sys`: For system-specific parameters and functions.

### MotionDetector Class

The `MotionDetector` class encapsulates the functionality for motion detection and video recording.

#### Constructor

- **Parameters**:
  - `video_source`: The source of the video (camera or video file).
  - `base_output_folder`: The base folder where recorded videos will be saved.
  - `min_area`: Minimum area of detected contours to consider as motion.
  - `threshold`: Threshold value for motion detection.
  - `frame_skip`: Number of frames to skip for processing.
  - `save_interval`: Time interval for saving video segments.

#### Methods

- **safe_create_folder(folder_path)**: Creates a folder if it does not exist, with error handling.
- **initialize_video_capture()**: Initializes video capture with retry logic.
- **initialize_hourly_folder()**: Creates a folder structure based on the current date and hour, and initializes a file list for FFmpeg.
- **initialize_video_writer(frame_width, frame_height)**: Initializes a video writer with a timestamped filename.
- **update_file_list(video_file)**: Appends the newly created video file to the FFmpeg concatenation list.
- **process_frame(prev_gray, frame)**: Processes a frame to detect motion by comparing it with the previous frame.
- **add_overlays(frame, frame_count)**: Adds informational overlays to the video frame.
- **cleanup()**: Cleans up resources, releasing the video writer.
- **signal_handler(sig, frame)**: Handles termination signals for graceful shutdown.
- **run()**: Main loop for capturing video, processing frames, and detecting motion.

### Main Execution

The script starts by defining the video source and output folder. It then enters a loop to start the motion detection process in a separate multiprocessing context. If the process crashes, it logs the error and restarts the detection process after a brief pause.

## Usage

1. **Set the Video Source**: Change the `VIDEO_SOURCE` variable to your desired video source (e.g., camera index or video file path).
2. **Run the Script**: Execute the script in a Python environment with OpenCV installed.
3. **Check Output**: Recorded videos will be saved in the `motion_recordings` folder, organized by date and hour.

## Requirements

- Python 3.x
- OpenCV (`cv2`)
- NumPy
- Logging module (included in Python standard library)

## Conclusion

This motion detection script provides a robust solution for capturing and recording motion events in video streams. It can be further enhanced with additional features such as email notifications, cloud storage integration, or real-time alerts.