import cv2
import os
import numpy as np
import time
import multiprocessing

def create_output_folder(folder_path):
    """Create the output folder if it doesn't exist."""
    if not os.path.exists(folder_path):
        os.makedirs(folder_path)

def initialize_video_capture(video_path):
    """Initialize video capture and return the VideoCapture object."""
    cap = cv2.VideoCapture(video_path)
    if not cap.isOpened():
        raise ValueError("Error opening video file")
    return cap

def initialize_video_writer(frame_width, frame_height, output_path):
    """Initialize the video writer for saving output video."""
    fourcc = cv2.VideoWriter_fourcc(*'mp4v')  # Codec for MP4
    return cv2.VideoWriter(output_path, fourcc, 20.0, (frame_width, frame_height))

def process_frame(prev_gray, frame, min_area, threshold, last_motion_timer):
    """Process a single video frame to detect motion."""
    # Convert current frame to grayscale and blur it
    gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
    gray = cv2.GaussianBlur(gray, (21, 21), 0)

    # Compute absolute difference between current frame and previous frame
    frame_delta = cv2.absdiff(prev_gray, gray)
    thresh = cv2.threshold(frame_delta, threshold, 255, cv2.THRESH_BINARY)[1]

    # Dilate the thresholded image to fill in holes
    thresh = cv2.dilate(thresh, None, iterations=2)

    # Find contours on thresholded image
    contours, _ = cv2.findContours(thresh.copy(), cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

    motion_detected = False
    for contour in contours:
        if cv2.contourArea(contour) >= min_area:
            motion_detected = True
            last_motion_timer = time.time()
            break

    return gray, motion_detected, last_motion_timer

def overlay_text(frame, frame_count):
    """Overlay time and recording status on the frame."""
    # Calculate and display time overlay
    total_seconds = round(frame_count / 15)
    overlay_str = f"m : {total_seconds // 60} s : {total_seconds % 60}"
    cv2.putText(frame, overlay_str, (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 255, 0), 1, cv2.LINE_AA)

def recording_indicator(frame):
    """Add a recording indicator to the frame."""
    reci = "." if int(time.time()) % 2 == 0 else ""
    cv2.putText(frame, f"Rec{reci}", (frame.shape[1] - 100, 30), cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 0, 255), 2, cv2.LINE_AA)

def detect_motion_frames(video_path, output_folder, min_area=400, threshold=25, frame_skip=2):
    """Detect motion in a video and save frames where motion is detected."""
    on_motion_record_seconds = 1  # Record for min seconds on motion
    last_motion_timer = 0
    motion_frames = 0
    frame_count = 0

    create_output_folder(output_folder)
    cap = initialize_video_capture(video_path)

    # Read first frame and initialize background detector
    ret, prev_frame = cap.read()
    if not ret:
        print("Error reading video")
        return

    prev_gray = cv2.cvtColor(prev_frame, cv2.COLOR_BGR2GRAY)
    prev_gray = cv2.GaussianBlur(prev_gray, (21, 21), 0)

    frame_width = int(cap.get(cv2.CAP_PROP_FRAME_WIDTH))
    frame_height = int(cap.get(cv2.CAP_PROP_FRAME_HEIGHT))
    out = initialize_video_writer(frame_width, frame_height, 'outputnew.mp4')

    while True:
        ret, frame = cap.read()
        if not ret:
            break

        # Skip frames to reduce processing load
        if frame_count % frame_skip != 0:
            frame_count += 1
            continue

        prev_gray, motion_detected, last_motion_timer = process_frame(prev_gray, frame, min_area, threshold, last_motion_timer)

        overlay_text(frame, frame_count)

        if motion_detected or time.time() - last_motion_timer <= on_motion_record_seconds:
            recording_indicator(frame)  # Add recording indicator
            out.write(frame)  # Save the frame to the video file
            motion_frames += 1

        frame_count += 1

    cap.release()
    out.release()
    print(f"Processed {frame_count} frames")
    print(f"Saved {motion_frames} frames with motion to {output_folder}")

if __name__ == "__main__":
    start = time.time()
    input_video = "gm.mp4"  # Replace with your video file path
    output_dir = "motion_frames"

    # Create a process for motion detection
    motion_process = multiprocessing.Process(target=detect_motion_frames, args=(input_video, output_dir))
    motion_process.start()

    # Wait for the motion detection process to finish
    motion_process.join()

    end = time.time()
    print(f"Time taken to run the code was {end - start} seconds")
