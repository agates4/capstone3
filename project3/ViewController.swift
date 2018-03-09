//
//  ViewController.swift
//  project3
//
//  Created by Aron Gates on 1/24/18.
//  Copyright © 2018 Aron Gates. All rights reserved.
//

import UIKit
import GoogleMaps
import CoreMotion
import MapKit
import CoreLocation

class ViewController: UIViewController, GMSPanoramaViewDelegate, CLLocationManagerDelegate  {
    
    var SCALE:CGFloat?
    var OFFSET:CGFloat?
    
    let motionManager = CMMotionManager()
    var locationManager = CLLocationManager()
    
    func panoramaView(_ view: GMSPanoramaView, didMoveTo panorama: GMSPanorama?) {
        print(panorama?.coordinate as Any)
    }
    
    func panoramaView(_ panoramaView: GMSPanoramaView, didTap marker: GMSMarker) -> Bool {
        print("\n\n")
        let infoPoint = convertLatLongCoord(latLong: CGPoint(x: marker.position.longitude, y: marker.position.latitude))
        print(infoPoint)
        return true
    }
    
    func locationManager(_ manager: CLLocationManager, didUpdateLocations locations: [CLLocation]) {
        guard let locValue: CLLocationCoordinate2D = manager.location?.coordinate else { return }
        (self.view as! GMSPanoramaView).moveNearCoordinate(CLLocationCoordinate2D(latitude: locValue.latitude, longitude: locValue.longitude))
    }
    
    func convertLatLongCoord(latLong: CGPoint) -> CGPoint {
        let x:CGFloat = 6371 * cos(latLong.x) * cos(latLong.y) * SCALE! + OFFSET!
        let y:CGFloat = 6371 * cos(latLong.x) * sin(latLong.y) * SCALE! + OFFSET!
        return CGPoint(x: x, y: y)
    }

    func panoramaView(_ panoramaView: GMSPanoramaView, didTap point: CGPoint) {
        print(point)
    }
    
    override func loadView() {
        // For use in foreground
        self.locationManager.requestWhenInUseAuthorization()
        if (CLLocationManager.locationServicesEnabled())
        {
            locationManager.delegate = self
            locationManager.desiredAccuracy = kCLLocationAccuracyNearestTenMeters
            locationManager.startUpdatingLocation()
        }
        
        let panoView = GMSPanoramaView(frame: .zero)
        panoView.delegate = self
        self.view = panoView
        
        let screen = UIScreen.main.bounds
        let screenSize = screen.size
        
        SCALE = min(screenSize.width, screenSize.height) / (2.0 * 6371);
        OFFSET = min(screenSize.width, screenSize.height) / 2.0;
        
        // Create a marker in paris
        let position = CLLocationCoordinate2D(latitude: 48.858, longitude: 2.284)
        let marker = GMSMarker(position: position)
        marker.title = "Hello World"
        marker.snippet = "Population: 8,174,100"
        marker.zIndex = 100
        
        
        // Add the marker to a GMSPanoramaView object named panoView
        marker.panoramaView = panoView
        
        print("\n\n\n")
        
        
        guard let locValue: CLLocationCoordinate2D = locationManager.location?.coordinate else { return }
        print("locations = \(locValue.latitude) \(locValue.longitude)")
        panoView.moveNearCoordinate(CLLocationCoordinate2D(latitude: locValue.latitude, longitude: locValue.longitude))
        
        motionManager.startDeviceMotionUpdates()
        if motionManager.isGyroAvailable {
            motionManager.startGyroUpdates(to: OperationQueue.main, withHandler: { (gyroData: CMGyroData?, error: Error?) in
                
                // needed to figure out the rotation
                let y = (gyroData?.rotationRate.y)!
                
                let motion = self.motionManager.deviceMotion
                
                if(motion?.attitude.pitch != nil) {
                    // calculate the pitch movement (up / down) I subtract 40 just as
                    // an offset to the view so it's more at face level.
                    // the -40 is optional, can be changed to anything.
                    let pitchCamera = GMSPanoramaCameraUpdate.setPitch( CGFloat(motion!.attitude.pitch).radiansToDegrees - 40 )
                    
                    // rotation calculation (left / right)
                    let rotateCamera = GMSPanoramaCameraUpdate.rotate(by: -CGFloat(y) )
                    
                    // rotate camera immediately
                    panoView.updateCamera(pitchCamera, animationDuration: 0)
                    
                    // for some reason, when trying to update camera
                    // immediately after one another, it will fail
                    // here we are dispatching after 1 millisecond for success
                    DispatchQueue.main.asyncAfter(deadline: .now() + 0.0001, execute: {
                        panoView.updateCamera(rotateCamera, animationDuration: 0)
                    })
                    
                }
            })
        }
    }
    
}


extension BinaryInteger {
    var degreesToRadians: CGFloat { return CGFloat(Int(self)) * .pi / 180 }
}

extension FloatingPoint {
    var degreesToRadians: Self { return self * .pi / 180 }
    var radiansToDegrees: Self { return self * 180 / .pi }
}
