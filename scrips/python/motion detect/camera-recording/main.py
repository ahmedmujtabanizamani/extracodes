import cv2
import os
import numpy as np
import time
import multiprocessing
import logging
from datetime import datetime
import signal
import sys

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('motion_detection.log'),
        logging.StreamHandler()
    ]
)

class MotionDetector:
    def __init__(self, video_source, base_output_folder, min_area=400, threshold=25, frame_skip=2, save_interval=60):
        self.video_source = video_source
        self.base_output_folder = base_output_folder
        self.min_area = min_area
        self.threshold = threshold
        self.frame_skip = frame_skip
        self.save_interval = save_interval  # Save every X seconds
        self.running = True
        self.last_motion_time = 0
        self.motion_frames = 0
        self.total_frames = 0
        self.on_motion_record_seconds = 1
        self.current_output_file = None
        self.out = None
        self.start_time = time.time()
        self.current_hour_folder = None
        self.file_list_path = None

    def safe_create_folder(self, folder_path):
        """Safely create output folder with error handling"""
        try:
            if not os.path.exists(folder_path):
                os.makedirs(folder_path)
                logging.info(f"Created output folder: {folder_path}")
            return True
        except Exception as e:
            logging.error(f"Failed to create folder {folder_path}: {str(e)}")
            return False

    def initialize_video_capture(self):
        """Initialize video capture with retry logic"""
        for attempt in range(5):
            try:
                cap = cv2.VideoCapture(self.video_source)
                if cap.isOpened():
                    logging.info(f"Successfully opened video source: {self.video_source}")
                    return cap
                else:
                    raise RuntimeError("Video capture not opened")
            except Exception as e:
                logging.warning(f"Attempt {attempt + 1} failed to open video source: {str(e)}")
                time.sleep(10)
        logging.error(f"Failed to open video source after 5 attempts")
        return None

    def initialize_hourly_folder(self):
        """Create hourly folder structure and initialize file list"""
        now = datetime.now()
        hour_folder = os.path.join(self.base_output_folder, now.strftime("%Y-%m-%d"), now.strftime("%H"))
        self.safe_create_folder(hour_folder)

        # Initialize file list for FFmpeg
        self.file_list_path = os.path.join(hour_folder, "file_list.txt")
        if not os.path.exists(self.file_list_path):
            with open(self.file_list_path, 'w') as f:
                f.write("# FFmpeg concatenation list\n")

        return hour_folder

    def initialize_video_writer(self, frame_width, frame_height):
        """Initialize video writer with timestamped filename"""
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        output_path = os.path.join(self.current_hour_folder, f"motion_{timestamp}.mp4")
        try:
            fourcc = cv2.VideoWriter_fourcc(*'mp4v')
            out = cv2.VideoWriter(output_path, fourcc, 20.0, (frame_width, frame_height))
            if out.isOpened():
                logging.info(f"Initialized video writer: {output_path}")
                self.update_file_list(output_path)  # Update file list for FFmpeg
                return out, output_path
            raise RuntimeError("Video writer not opened")
        except Exception as e:
            logging.error(f"Failed to initialize video writer: {str(e)}")
            return None, None

    def update_file_list(self, video_file):
        """Append new video file to the concatenation list"""
        try:
            with open(self.file_list_path, 'a') as f:
                rel_path = os.path.relpath(video_file, os.path.dirname(self.file_list_path))
                f.write(f"file '{rel_path}'\n")
            logging.info(f"Updated file list with: {video_file}")
        except Exception as e:
            logging.error(f"Error updating file list: {str(e)}")

    def process_frame(self, prev_gray, frame):
        """Process frame to detect motion"""
        gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
        gray = cv2.GaussianBlur(gray, (21, 21), 0)
        
        frame_delta = cv2.absdiff(prev_gray, gray)
        thresh = cv2.threshold(frame_delta, self.threshold, 255, cv2.THRESH_BINARY)[1]
        thresh = cv2.dilate(thresh, None, iterations=2)
        
        contours, _ = cv2.findContours(thresh.copy(), cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
        
        motion_detected = False
        for contour in contours:
            if cv2.contourArea(contour) >= self.min_area:
                motion_detected = True
                self.last_motion_time = time.time()
                break
        
        return gray, motion_detected

    def add_overlays(self, frame, frame_count):
        """Add informational overlays to frame"""
        total_seconds = round(frame_count / 15 / 2)
        overlay_str = f"m : {total_seconds // 60} s : {total_seconds % 60}"
        cv2.putText(frame, overlay_str, (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 255, 0), 1, cv2.LINE_AA)
        
        reci = "." if int(time.time()) % 2 == 0 else ""
        cv2.putText(frame, f"Rec{reci}", (frame.shape[1] - 100, 30), cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 0, 255), 2, cv2.LINE_AA)

    def cleanup(self):
        """Clean up resources"""
        if self.out and self.out.isOpened():
            self.out.release()
        logging.info("Resources cleaned up successfully")

    def signal_handler(self, sig, frame):
        """Handle termination signals"""
        logging.info("Signal received, shutting down gracefully...")
        self.running = False

    def run(self):
        """Main detection loop with error recovery"""
        signal.signal(signal.SIGINT, self.signal_handler)
        signal.signal(signal.SIGTERM, self.signal_handler)

        while self.running:
            cap = self.initialize_video_capture()
            if not cap:
                time.sleep(10)
                continue
            
            ret, prev_frame = cap.read()
            if not ret:
                logging.error("Failed to read first frame")
                time.sleep(10)
                continue
            
            frame_width = int(cap.get(cv2.CAP_PROP_FRAME_WIDTH))
            frame_height = int(cap.get(cv2.CAP_PROP_FRAME_HEIGHT))
            self.current_hour_folder = self.initialize_hourly_folder()
            self.out, self.current_output_file = self.initialize_video_writer(frame_width, frame_height)
            if not self.out:
                time.sleep(10)
                continue
            
            prev_gray = cv2.cvtColor(prev_frame, cv2.COLOR_BGR2GRAY)
            prev_gray = cv2.GaussianBlur(prev_gray, (21, 21), 0)
            frame_count = 0
            
            while self.running:
                ret, frame = cap.read()
                if not ret:
                    logging.warning("Failed to read frame - restarting capture")
                    break
                
                if frame_count % self.frame_skip != 0:
                    frame_count += 1
                    continue
                
                prev_gray, motion_detected = self.process_frame(prev_gray, frame)
                self.add_overlays(frame, frame_count)
                
                if motion_detected or time.time() - self.last_motion_time <= self.on_motion_record_seconds:
                    self.out.write(frame)
                    self.motion_frames += 1
                
                self.total_frames += 1
                frame_count += 1
                
                # Save video every save_interval seconds
                if time.time() - self.start_time >= self.save_interval:
                    self.cleanup()  # Close current writer
                    self.out, self.current_output_file = self.initialize_video_writer(frame_width, frame_height)
                    self.start_time = time.time()
                
                # Periodic status logging
                if frame_count % 100 == 0:
                    logging.info(f"Processed {self.total_frames} frames (Motion frames: {self.motion_frames})")
            
            self.cleanup()
            cap.release()

def run_detector(video_source, output_folder):
    """Wrapper function for multiprocessing"""
    detector = MotionDetector(video_source, output_folder)
    detector.run()

if __name__ == "__main__":
    VIDEO_SOURCE = 0  # Change to your video source (e.g., file path or RTSP URL)
    OUTPUT_FOLDER = "motion_recordings"
    
    while True:
        try:
            logging.info("Starting motion detection process")
            process = multiprocessing.Process(
                target=run_detector,
                args=(VIDEO_SOURCE, OUTPUT_FOLDER)
            )
            process.start()
            process.join()  # Will block until process dies
            
            if process.exitcode != 0:
                logging.error(f"Detection process crashed with exit code {process.exitcode}")
            
            logging.info("Restarting detection process in 10 seconds...")
            time.sleep(10)
            
        except KeyboardInterrupt:
            logging.info("Shutting down gracefully...")
            break
        except Exception as e:
            logging.error(f"Main process error: {str(e)}")
            time.sleep(30)
