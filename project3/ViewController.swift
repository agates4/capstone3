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
import PopupDialog

import Alamofire
import SwiftyJSON

class ViewController: UIViewController, GMSPanoramaViewDelegate, CLLocationManagerDelegate  {
    
    var SCALE:CGFloat?
    var OFFSET:CGFloat?
    
    let motionManager = CMMotionManager()
    var locationManager = CLLocationManager()
    var lastLocation = CLLocationCoordinate2D()
    var lastCalled = "WILL MOVE TO"
    var POIList : [[String]] = []
    
    func getPOIS() {
        let urlString = "http://capstone3.herokuapp.com/new/get_pois.php"
        
        let parameters: [String: AnyObject] = [
            "latitude" : locationManager.location!.coordinate.latitude as AnyObject,
            "longitude" : locationManager.location!.coordinate.longitude as AnyObject
        ]
        
        Alamofire.request(urlString, method: .post, parameters: parameters, encoding: URLEncoding.httpBody).responseJSON {
            response in
            
            switch response.result {
            case .success(let value):
                let json = JSON(value)
                
                var bigArray : [[String]] = []
                
                for (_,subJson):(String, JSON) in json {
                    
                    var smallArray : [String] = []
                    
                    for (_,subJson2):(String, JSON) in subJson {
                        smallArray.append(subJson2.stringValue)
                    }
                    
                    bigArray.append(smallArray)
                }
                self.addMarker(bigPOIList: bigArray)
                
                break
            case .failure(let error):
                print(error)
                break
            }
        }
    }
    
    func addMarker(bigPOIList: [[String]]) {
        // Create a marker in paris
        self.POIList = bigPOIList
        
        for POI in bigPOIList {
            let latitude = POI[3]
            let longitude = POI[1]
            let ID = POI[4]
            
            let position = CLLocationCoordinate2D(latitude: Double(latitude)!, longitude: Double(longitude)!)
            let marker = GMSMarker(position: position)
            marker.title = ID
            
            // Add the marker to a GMSPanoramaView object named panoView
            marker.panoramaView = (self.view as! GMSPanoramaView)
        }
    }
    
    func panoramaView(_ view: GMSPanoramaView, didMoveTo panorama: GMSPanorama?) {
        print("DID MOVE TO")
        if (lastCalled == "WILL MOVE TO") {
            toggleGyroUpdates(toggle: true)
        } else {
            toggleGyroUpdates(toggle: false)
            toggleGyroUpdates(toggle: true)
        }
        lastCalled = "DID MOVE TO"
    }
    
    func panoramaView(_ view: GMSPanoramaView, willMoveToPanoramaID panoramaID: String) {
        print("WILL MOVE TO")
        if (lastCalled == "DID MOVE TO") {
            toggleGyroUpdates(toggle: false)
        }
        lastCalled = "WILL MOVE TO"
    }
    
    func toggleGyroUpdates(toggle: Bool) {
        if (toggle) {
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
                        let pitchCamera = GMSPanoramaCameraUpdate.setPitch( CGFloat(motion!.attitude.pitch).radiansToDegrees - 80 )
                        
                        // rotation calculation (left / right)
                        let rotateCamera = GMSPanoramaCameraUpdate.rotate(by: -CGFloat(y) )
                        
                        // rotate camera immediately
                        (self.view as! GMSPanoramaView).updateCamera(pitchCamera, animationDuration: 0.001)
                        
                        // for some reason, when trying to update camera
                        // immediately after one another, it will fail
                        // here we are dispatching after 1 millisecond for success
                        DispatchQueue.main.asyncAfter(deadline: .now() + 0.0001, execute: {
                            (self.view as! GMSPanoramaView).updateCamera(rotateCamera, animationDuration: 0.001)
                        })
                        
                    }
                    
                })
            }
        }
        else {
            motionManager.stopGyroUpdates()
            motionManager.stopDeviceMotionUpdates()
        }
    }
    
    func panoramaView(_ panoramaView: GMSPanoramaView, didTap marker: GMSMarker) -> Bool {
        // Prepare the popup assets
        
        let ID = marker.title!
        var ourPOI : [String] = []
        for POI in self.POIList {
            let ID2 = POI[4]
            
            if(ID == ID2) {
                ourPOI = POI
                break
            }
        }
        
        let latitude = ourPOI[3]
        let longitude = ourPOI[1]
        let title2 = ourPOI[2]
        let description = ourPOI[0]
        
        let title = title2
        let message = "Description: " + description + "\nLatitude: " + latitude + "\nLongitude: " + longitude
        
        // Create the dialog
        let popup = PopupDialog(title: title, message: message) {
            self.toggleGyroUpdates(toggle: true)
        }
        self.present(popup, animated: true) {
            self.toggleGyroUpdates(toggle: false)
        }
        
        return true
    }
    
    func locationManager(_ manager: CLLocationManager, didUpdateLocations locations: [CLLocation]) {
        guard let locValue: CLLocationCoordinate2D = manager.location?.coordinate else { return }
        
        let distance = CLLocation.distance(from: locValue, to: lastLocation)
        if (distance > 10) {
            print("UPDATING LOCATION")
            (self.view as! GMSPanoramaView).moveNearCoordinate(CLLocationCoordinate2D(latitude: locValue.latitude, longitude: locValue.longitude))
            lastLocation = locValue
        }
        
    }
    
    override func loadView() {
        // For use in foreground
        self.locationManager.requestWhenInUseAuthorization()
        if (CLLocationManager.locationServicesEnabled())
        {
            locationManager.delegate = self
            locationManager.desiredAccuracy = kCLLocationAccuracyBest
            locationManager.startUpdatingLocation()
        }
        
        let panoView = GMSPanoramaView(frame: .zero)
        panoView.delegate = self
        self.view = panoView
        
        let screen = UIScreen.main.bounds
        let screenSize = screen.size
        
        SCALE = min(screenSize.width, screenSize.height) / (2.0 * 6371);
        OFFSET = min(screenSize.width, screenSize.height) / 2.0;
        
        guard let locValue: CLLocationCoordinate2D = locationManager.location?.coordinate else { return }
        lastLocation = locValue
        print("locations = \(locValue.latitude) \(locValue.longitude)")
        
        getPOIS()
        
        panoView.moveNearCoordinate(CLLocationCoordinate2D(latitude: locValue.latitude, longitude: locValue.longitude))
        
    }
    
}


extension BinaryInteger {
    var degreesToRadians: CGFloat { return CGFloat(Int(self)) * .pi / 180 }
}

extension FloatingPoint {
    var degreesToRadians: Self { return self * .pi / 180 }
    var radiansToDegrees: Self { return self * 180 / .pi }
}

extension CLLocation {
    
    /// Get distance between two points
    ///
    /// - Parameters:
    ///   - from: first point
    ///   - to: second point
    /// - Returns: the distance in meters
    class func distance(from: CLLocationCoordinate2D, to: CLLocationCoordinate2D) -> CLLocationDistance {
        let from = CLLocation(latitude: from.latitude, longitude: from.longitude)
        let to = CLLocation(latitude: to.latitude, longitude: to.longitude)
        return from.distance(from: to)
    }
}
