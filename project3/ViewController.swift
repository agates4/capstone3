//
//  ViewController.swift
//  project3
//
//  Created by Aron Gates on 1/24/18.
//  Copyright © 2018 Aron Gates. All rights reserved.
//

import UIKit
import GoogleMaps

class ViewController: UIViewController, GMSPanoramaViewDelegate  {
    
    var SCALE:CGFloat?
    var OFFSET:CGFloat?
    
    func panoramaView(_ view: GMSPanoramaView, didMoveTo panorama: GMSPanorama?) {
        print(panorama?.coordinate as Any)
    }
    
    func panoramaView(_ panoramaView: GMSPanoramaView, didTap marker: GMSMarker) -> Bool {
        print("\n\n")
        let infoPoint = convertLatLongCoord(latLong: CGPoint(x: marker.position.longitude, y: marker.position.latitude))
        print(infoPoint)
        return true
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
        
        panoView.moveNearCoordinate(CLLocationCoordinate2D(latitude: 48.858, longitude: 2.284))
    }
    
}

